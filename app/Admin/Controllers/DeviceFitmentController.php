<?php

namespace App\Admin\Controllers;

use App\Models\DeviceFitment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class DeviceFitmentController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('电梯装修价')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('电梯装修价')
            ->description('详细')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('电梯装修价')
            ->description('修改')
            ->body($this->form(true)->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('电梯装修价')
            ->description('新增')
            ->body($this->form(false));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DeviceFitment);

        $grid->id('ID');
        $grid->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $grid->name('装饰项目名称');
        $grid->stuff('材料');
        $grid->spec('规格编号');
        $grid->unit('单位');
        $grid->price('单价');
        $grid->desc('描述');
        $grid->has_in_base('是否标配/是否在基础价格包含');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '装饰项目名称');
            $filter->like('stuff', '材料');
            $filter->like('spec', '规格编号');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(DeviceFitment::findOrFail($id));

        $show->id('ID');
        $show->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $show->name('装饰项目名称');
        $show->stuff('材料');
        $show->spec('规格编号');
        $show->unit('单位');
        $show->price('单价');
        $show->desc('描述');
        $show->has_in_base('是否标配/是否在基础价格包含');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($hasEdit=false)
    {
        $form = new Form(new DeviceFitment);

        if($hasEdit){
            $form->display('device','已选电梯设备')->with(function ($value) {
                return 'ID:'.implode('|',json_decode(json_encode($value), true));
            });
        }
        $form->select('_brand','电梯品牌')->options('/admin/device/brands')
            ->load('did', '/admin/device/brandsDetail');
        $form->select('did','电梯设备');
        $form->divide();

        $form->text('name','装饰项目名称');
        $form->text('stuff','材料');
        $form->text('spec','规格编号');
        $form->text('unit','单位');
        $form->text('price','单价');
        $form->text('has_in_base','是否标配/是否在基础价格包含');
        $form->text('desc','描述');

        return $form;
    }
}
