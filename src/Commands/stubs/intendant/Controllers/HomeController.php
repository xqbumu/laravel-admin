<?php

namespace Intendant\{$stub_intendant_zone_upper}\Controllers;

use Intendant\{$stub_intendant_zone_upper}\Controllers\Controller;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;
use Inchow\Incore\Layout\Column;
use Inchow\Incore\Layout\Content;
use Inchow\Incore\Layout\Row;
use Inchow\Incore\Widgets\Box;
use Inchow\Incore\Widgets\Chart\Bar;
use Inchow\Incore\Widgets\Chart\Doughnut;
use Inchow\Incore\Widgets\Chart\Line;
use Inchow\Incore\Widgets\Chart\Pie;
use Inchow\Incore\Widgets\Chart\PolarArea;
use Inchow\Incore\Widgets\Chart\Radar;
use Inchow\Incore\Widgets\Collapse;
use Inchow\Incore\Widgets\InfoBox;
use Inchow\Incore\Widgets\Tab;
use Inchow\Incore\Widgets\Table;


class HomeController extends Controller
{
    public function index()
    {
        // dump(\Route::current());
        return Incore::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $content->row(function ($row) {
                $row->column(3, new InfoBox('New Users', 'users', 'aqua', '/intendant/users', '1024'));
                $row->column(3, new InfoBox('New Orders', 'shopping-cart', 'green', '/intendant/orders', '150%'));
                $row->column(3, new InfoBox('Articles', 'book', 'yellow', '/intendant/articles', '2786'));
                $row->column(3, new InfoBox('Documents', 'file', 'red', '/intendant/files', '698726'));
            });

            $content->row(function (Row $row) {

                $row->column(6, function (Column $column) {

                    $tab = new Tab();

                    $pie = new Pie([
                        ['Stracke Ltd', 450], ['Halvorson PLC', 650], ['Dicki-Braun', 250], ['Russel-Blanda', 300],
                        ['Emmerich-O\'Keefe', 400], ['Bauch Inc', 200], ['Leannon and Sons', 250], ['Gibson LLC', 250],
                    ]);

                    $tab->add('Pie', $pie);
                    $tab->add('Table', new Table());
                    $tab->add('Text', 'blablablabla....');

                    $tab->dropDown([['Orders', '/intendant/orders'], ['intendantistrators', '/intendant/intendantistrators']]);
                    $tab->title('Tabs');

                    $column->append($tab);

                    $collapse = new Collapse();

                    $bar = new Bar(
                        ["January", "February", "March", "April", "May", "June", "July"],
                        [
                            ['First', [40,56,67,23,10,45,78]],
                            ['Second', [93,23,12,23,75,21,88]],
                            ['Third', [33,82,34,56,87,12,56]],
                            ['Forth', [34,25,67,12,48,91,16]],
                        ]
                    );
                    $collapse->add('Bar', $bar);
                    $collapse->add('Orders', new Table());
                    $column->append($collapse);

                    $doughnut = new Doughnut([
                        ['Chrome', 700],
                        ['IE', 500],
                        ['FireFox', 400],
                        ['Safari', 600],
                        ['Opera', 300],
                        ['Navigator', 100],
                    ]);
                    $column->append((new Box('Doughnut', $doughnut))->removable()->collapsable()->style('info'));
                });

                $row->column(6, function (Column $column) {

                    $column->append(new Box('Radar', new Radar()));

                    $polarArea = new PolarArea([
                        ['Red', 300],
                        ['Blue', 450],
                        ['Green', 700],
                        ['Yellow', 280],
                        ['Black', 425],
                        ['Gray', 1000],
                    ]);
                    $column->append((new Box('Polar Area', $polarArea))->removable()->collapsable());

                    $column->append((new Box('Line', new Line()))->removable()->collapsable()->style('danger'));
                });

            });

            $headers = ['Id', 'Email', 'Name', 'Company', 'Last Login', 'Status'];
            $rows = [
                [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica', '1997-08-13 13:59:21', 'open'],
                [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar', '1988-07-19 03:19:08', 'blocked'],
                [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC', '1978-06-19 11:12:57', 'blocked'],
                [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor', '1988-09-07 23:57:45', 'open'],
                [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.', 'Braun Ltd', '2013-10-16 10:00:01', 'open'],
            ];

            $content->row((new Box('Table', new Table($headers, $rows)))->style('info')->solid());
        });
    }
}
