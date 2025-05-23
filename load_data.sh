  #!/bin/bash

  TABLE_NAME="test_table"
  FILE_DIR="/var/lib/mysql-files/Number"  # Директория с файлами
  DB_USER="root"
  DB_PASS="wT8gn!RpC2p/z.M5"
  DB_NAME="where-parts-db"

  for file in "$FILE_DIR"/*.txt; do  # Предполагаем, что все файлы имеют расширение .txt
    if [ -f "$file" ]; then
      echo "Loading data from: $file"
      mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "
        LOAD DATA INFILE '$file'
        INTO TABLE $TABLE_NAME
        FIELDS TERMINATED BY ';'
        LINES TERMINATED BY '\n';
      "
      if [ $? -eq 0 ]; then
        echo "Successfully loaded data from $file"
      else
        echo "Error loading data from $file"
      fi
    fi
  done
