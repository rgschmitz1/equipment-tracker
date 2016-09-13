#!/bin/bash
if [ -n "$1" ] && [ "$1" = '-a' ]; then
    echo 'Auto importing product info...'
    autopopulate=true
elif [ -n "$1" ]; then
    echo 'Invalid option'
    exit 1
else
    autopopulate=false
fi
TMPFILE=$(mktemp /tmp/list.XXXXXX)

# Generate a sql script
cat >> ${TMPFILE}.sql << 'EOF'
USE manufacturing;
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT,
    `product` VARCHAR(30),
    `description` VARCHAR(120),
    `serial` INT(8) UNSIGNED ZEROFILL,
    `last_claim_id` INT,
    PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `claim_history` (
    `id` INT AUTO_INCREMENT,
    `product_id` INT,
    `user_id` INT DEFAULT 1,
    `claim_date` DATETIME,
    `approved` TINYINT(1),
    PRIMARY KEY (`id`)
);
EOF

# Dump MySQL data to temp file
echo "SELECT Product, CfgDesc, SerNum
FROM prod_tracking.TagInfo
WHERE Company = 'Manufacturing - Madison'" |
mysql -N -h db -u xes -pxes-inc engrel | sed "s/'/\\\'/g; s/\t/;/g" > $TMPFILE

# Parse MySQL data and append to end of sql script
end=$(wc -l $TMPFILE | awk '{print $1}')
for line in $(seq 1 $end); do
    data=$(awk "NR==$line" $TMPFILE)
    product=$(awk -F ';' '{print $1}' <<< $data)
    description=$(awk -F ';' '{print $2}' <<< $data)
    serial=$(awk -F ';' '{print $3}' <<< $data)
    if [ -z "$serial" ] || [ -z "$product" ] || [ -z "$description" ]; then
        continue
    elif [ -n "$(echo "SELECT id FROM manufacturing.products WHERE serial=$serial LIMIT 1" | mysql -h engapps -u mfgtest -pw1-r_37B 2> /dev/null)" ]; then
        continue
    fi
    if [ -z "$last_claim_id" ]; then
        last_claim_id=$(($(echo 'SELECT MAX(id) FROM manufacturing.claim_history' | mysql -N -h engapps -u mfgtest -pw1-r_37B 2> /dev/null) + 1))
    else
        let last_claim_id++
    fi
    echo "INSERT INTO products (\`serial\`, \`product\`, \`description\`, \`last_claim_id\`) VALUES ('$serial', '$product', '$description', '$last_claim_id');" >> ${TMPFILE}.sql
    if [ -z "$product_id" ]; then
        product_id=$(($(echo 'SELECT MAX(id) FROM manufacturing.products' | mysql -N -h engapps -u mfgtest -pw1-r_37B 2> /dev/null) + 1))
    else
        let product_id++
    fi
    echo "INSERT INTO claim_history (\`user_id\`, \`product_id\`, \`claim_date\`) VALUES ('1', '$product_id', NOW());" >> ${TMPFILE}.sql
done

# Remove temp file and output path of new sql script
rm $TMPFILE
if $autopopulate; then
    mysql -h engapps -u mfgtest -pw1-r_37B < ${TMPFILE}.sql
    stat=$?
    rm ${TMPFILE}.sql
else
    echo "mysql script is stored here, ${TMPFILE}.sql"
    stat=0
fi
exit $stat
