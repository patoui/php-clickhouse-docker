# PHP and ClickHouse Docker Setup

Using this as a sandbox to learn about how PHP and ClickHouse perform queries on large datasets.

This repo will use SeasX/SeasClick C++ extension to run ClickHouse queries.

## Installation

```bash
docker-compose up
```

## Web

Visit `localhost` or `127.0.0.1`

## Testing

Download the dataset found [here](https://clickhouse.com/docs/en/getting-started/example-datasets/opensky) within the `flight_data` directory

Update the file to remove `+00:00` timezone data
```bash
sed -i 's/\+00\:00//g' flightlist_20200401_20200430
```

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

Preliminary results for batch inserts (mileage may vary based on your hardward):
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
