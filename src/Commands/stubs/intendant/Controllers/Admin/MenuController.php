<?php

namespace Intendant\{$stub_intendant_zone_upper}\Controllers\Incore;

use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Menu as MenuModel;
use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Role;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;

use Inchow\Incore\Form;
use Inchow\Incore\Layout\Column;
use Inchow\Incore\Layout\Content;
use Inchow\Incore\Layout\Row;
use Inchow\Incore\Menu\Menu;
use Inchow\Incore\Widgets\Box;
use Inchow\Incore\Widgets\Callout;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class MenuController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Incore::content(function (Content $content) {
            $content->header(trans('incore::lang.menu'));
            $content->description(trans('incore::lang.list'));

            $content->row(function (Row $row) {
                $row->column(5, function (Column $column) {
                    $column->append($this->callout());

                    $form = new \Inchow\Incore\Widgets\Form();
                    $form->action(Incore::url('auth/menu'));

                    $options = [0 => 'Root'] + MenuModel::buildSelectOptions();
                    $form->select('parent_id', trans('incore::lang.parent_id'))->options($options);
                    $form->text('title', trans('incore::lang.title'))->rules('required');
                    $form->text('icon', trans('incore::lang.icon'))->default('fa-bars')->rules('required');
                    $form->text('uri', trans('incore::lang.uri'));
                    $form->multipleSelect('roles', trans('incore::lang.roles'))->options(Role::all()->pluck('name', 'id'));

                    $column->append((new Box(trans('incore::lang.new'), $form))->style('success'));
                });

                $menu = new Menu(new MenuModel());

                $row->column(7, $menu);
            });

            Incore::script($this->script());
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->action(
            '\Inchow\Incore\Controllers\MenuController@edit', ['id' => $id]
        );
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Incore::content(function (Content $content) use ($id) {
            $content->header(trans('incore::lang.menu'));
            $content->description(trans('incore::lang.edit'));

            $content->row($this->callout());
            $content->row($this->form()->edit($id));
        });
    }

    /**
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        if (Request::input('parent_id') == $id) {
            throw new \Exception(trans('incore::lang.parent_select_error'));
        }

        return $this->form()->update($id);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('incore::lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('incore::lang.delete_failed'),
            ]);
        }
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        if (Request::has('_order')) {
            $menu = new Menu(new MenuModel());

            return response()->json([
                'status' => $menu->saveTree(Request::input('_order')),
            ]);
        }

        return $this->form()->store();
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Incore::form(MenuModel::class, function (Form $form) {
            $form->display('id', 'ID');

            $options = [0 => 'Root'] + MenuModel::buildSelectOptions();

            $form->select('parent_id', trans('incore::lang.parent_id'))->options($options);
            $form->text('title', trans('incore::lang.title'))->rules('required');
            $form->text('icon', trans('incore::lang.icon'))->default('fa-bars')->rules('required');
            $form->text('uri', trans('incore::lang.uri'));
            $form->multipleSelect('roles', trans('incore::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('incore::lang.created_at'));
            $form->display('updated_at', trans('incore::lang.updated_at'));
        });
    }

    protected function script()
    {
        return <<<'EOT'

$('.menu-tools').on('click', function(e){
    var target = $(e.target),
        action = target.data('action');
    if (action === 'expand-all') {
        $('.dd').nestable('expandAll');
    }
    if (action === 'collapse-all') {
        $('.dd').nestable('collapseAll');
    }
});
EOT;
    }

    /**
     * @return Callout
     */
    protected function callout()
    {
        $text = 'For icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';

        return new Callout($text, 'Tips', 'info');
    }
}
