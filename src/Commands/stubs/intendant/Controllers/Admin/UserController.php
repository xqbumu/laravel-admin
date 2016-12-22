<?php

namespace Intendant\{$stub_intendant_zone_upper}\Controllers\Incore;

use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Administrator;
use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Permission;
use Intendant\{$stub_intendant_zone_upper}\Auth\Database\Role;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;

use Inchow\Incore\Form;
use Inchow\Incore\Grid;
use Inchow\Incore\Layout\Content;

use Illuminate\Routing\Controller;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Incore::content(function (Content $content) {
            $content->header(trans('incore::lang.administrator'));
            $content->description(trans('incore::lang.list'));
            $content->body($this->grid()->render());
        });
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
            $content->header(trans('incore::lang.administrator'));
            $content->description(trans('incore::lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Incore::content(function (Content $content) {
            $content->header(trans('incore::lang.administrator'));
            $content->description(trans('incore::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Incore::grid(Administrator::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->username(trans('incore::lang.username'));
            $grid->name(trans('incore::lang.name'));

            $grid->roles(trans('incore::lang.roles'))->value(function ($roles) {
                $roles = array_map(function ($role) {
                    return "<span class='label label-success'>{$role['name']}</span>";
                }, $roles);

                return implode('&nbsp;', $roles);
            });

            $grid->created_at(trans('incore::lang.created_at'));
            $grid->updated_at(trans('incore::lang.updated_at'));

            $grid->rows(function ($row) {
                if ($row->id == 1) {
                    $row->actions('edit');
                }
            });

            $grid->disableBatchDeletion();

            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Incore::form(Administrator::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('username', trans('incore::lang.username'))->rules('required');
            $form->text('name', trans('incore::lang.name'))->rules('required');
            $form->password('password', trans('incore::lang.password'))->rules('required');

            $form->multipleSelect('roles', trans('incore::lang.roles'))->options(Role::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('incore::lang.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->display('created_at', trans('incore::lang.created_at'));
            $form->display('updated_at', trans('incore::lang.updated_at'));

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
