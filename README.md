## TASK #1

[View Task #1](https://www.dropbox.com/s/3njmkozgjjdckuy/Interview_assignment_Distributed_Workers.pdf?dl=0)

### Installation
1) Create classmap `composer dumpautoload`
2) Run migration `php commands.php migration:up`
3) Run insert demo data `php commands.php insert-demo-data`
4) Run distributor `php queue/distributor.php`
5) Run as many workers as you need `php queue/worker.php`