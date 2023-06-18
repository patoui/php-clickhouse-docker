# PHP and ClickHouse Docker Setup

Using this as a sandbox to learn about how PHP and ClickHouse perform queries on large datasets.

This repo will use SeasX's [PHP extension](https://github.com/SeasX/SeasClick), lizhichao's [TCP client](https://github.com/lizhichao/one-ck), and smi2's [HTTP client](https://github.com/smi2/phpClickHouse). Note that SeasX's PHP extension uses the transmission control protocol as well (TCP).

## Installation

```bash
docker-compose up -d
```

## Testing

TLDR; vist the following URLs in order, when they're all done, open `execution_results.txt`

```
// insert data
http://localhost/?driver=extension&action=insert_bulk
http://localhost/?driver=tcp&action=insert_bulk
http://localhost/?driver=http&action=insert_bulk

// read data
http://localhost/?driver=extension&action=read
http://localhost/?driver=tcp&action=read
http://localhost/?driver=http&action=read
```

Note: the dataset originates from [here](https://clickhouse.com/docs/en/getting-started/example-datasets/opensky).

To test every available client type, simply add `?driver={value}&action={value}` to the URL.

Available drivers:
```
extension = SeasClick extension
tcp = TCP client lizhichao/one-ck
http = HTTP client smi2/phpclickhouse
```

Available actions:
```
insert_bulk = inserts batches of 1000 rows at a time, inserting a total of 842905 records
read = select the first 1000 rows 500 times
```

Example test `localhost?driver=tcp&action=insert_bulk`

## Results

Mileage may vary based on your hardware:
```
==== INSERT BULK ====
Extension = ~10s
TCP = ~17s
HTTP = ~21s

==== READ ====
Extension = ~5s
TCP = ~7s
HTTP = ~9s
```

## ClickHouse

To access ClickHouse run `docker exec -it -uroot tc_clickhouse /usr/bin/clickhouse --client`

#### PHPStorm Note

If you're using PHPStorm and want it recognize SeasClick class and it's methods please use this repository [patoui's PHPStorm Stubs](https://github.com/patoui/phpstorm-stubs/) and update your editors stubs to it's local path

PHPStorm via Settings -> Languages & Frameworks -> PHP -> PHP Runtime -> Advanced -> "Default stubs path"
