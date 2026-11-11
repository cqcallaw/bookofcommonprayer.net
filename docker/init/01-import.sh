#!/bin/bash
set -eu
echo "Importing BCP table dumps into n773365_bocpdb"
shopt -s nullglob
export MYSQL_PWD="${MARIADB_ROOT_PASSWORD}"
client=(mysql --protocol=socket -uroot -hlocalhost --max_allowed_packet=512M --database=n773365_bocpdb)
for f in /sql/n773365_bocpdb_table_*.sql; do
  echo "  $f"
  "${client[@]}" < "$f"
done
echo "  /sql/n773365_bocpdb_extra.sql"
"${client[@]}" < /sql/n773365_bocpdb_extra.sql
echo "Import finished"
"${client[@]}" -e "SHOW TABLES;"
