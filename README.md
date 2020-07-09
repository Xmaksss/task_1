## TASK #1

[View Task #1](https://www.dropbox.com/s/3njmkozgjjdckuy/Interview_assignment_Distributed_Workers.pdf?dl=0)

### Installation
1) Copy config `cp env-example .env`
2) Create classmap `composer dumpautoload`
3) Run migration `php commands.php migration:up`
4) Run insert demo data `php commands.php insert-demo-data`
5) Run distributor `php queue/distributor.php`
6) Run as many workers as you need `php queue/worker.php`
