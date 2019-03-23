<?php

namespace App\Admin\Controllers;

use App\Models\DeviceFreight;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class DeviceFreightController extends Controller
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
            ->header('电梯运费价')
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
            ->header('电梯运费价')
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
            ->header('电梯运费价')
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
            ->header('电梯运费价')
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
        $grid = new Grid(new DeviceFreight);

        $grid->id('ID');
        $grid->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $grid->from('发货地点')->display(function(){
            $arr=[$this->from_province,$this->from_city];
            if($this->from_district){
                array_push($arr,$this->from_district);
            }
            return implode('/',$arr);
        });
        $grid->to('到货地点')->display(function(){
            $arr=[$this->to_province,$this->to_city];
            if($this->to_district){
                array_push($arr,$this->to_district);
            }
            return implode('/',$arr);
        });
        $grid->price('单台价格')->money();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('from', '发货地点');
            $filter->like('to', '到货地点');
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->disableExport();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
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
        $show = new Show(DeviceFreight::findOrFail($id));

        $show->id('ID');
        $show->device('电梯设备')->display(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $show->from('发货地点');
        $show->to('到货地点');
        $show->price('单台价格');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($hasEdit=false)
    {
        $form = new Form(new DeviceFreight);

        if($hasEdit){
            $form->display('device','已选电梯设备')->with(function ($value) {
                return 'ID:'.implode('|',json_decode(json_encode($value), true));
            });
        }
        $form->select('_brand','电梯品牌')->options('/admin/device/brands')
            ->load('did', '/admin/device/brandsDetail');
        $form->select('did','电梯设备');
        $form->divide();

        //$form->text('from','发货地点');
        $form->distpicker(['from_province', 'from_city','from_district'], '发货城市')->autoselect(2);
        $form->distpicker(['to_province', 'to_city','to_district'], '收货城市')->autoselect(2);
        $form->currency('price','单台价格')->symbol('￥');
        //忽略字段
        $form->ignore(['_brand']);
        $form->saving(function (Form $form){
            $form->did=$form->did>0?$form->did:$form->model()->did;
        });
        return $form;
    }
}
