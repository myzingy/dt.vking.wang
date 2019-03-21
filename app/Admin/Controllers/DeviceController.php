<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

class DeviceController extends Controller
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
            ->header('电梯设备价')
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
            ->header('电梯设备价')
            ->description('查看')
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
            ->header('电梯设备价')
            ->description('修改')
            ->body($this->form()->edit($id));
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
            ->header('电梯设备价')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Device);

        $grid->id('ID');
        $grid->brand('品牌');
        $grid->brand_set('品牌系列');
        $grid->dload('载重(kg)');
        $grid->speedup('速度(m/s)');
        $grid->floor('楼层');
        $grid->hoisting_height('标准提升高度(m)');
        $grid->freeboard_dev('设备超米费（元/米）');
        $grid->freeboard_ins('安装超米费（元/米）');
        $grid->device_price('设备单价');
        $grid->device_rate('设备税率');
        $grid->install_price('安装单价');
        $grid->install_rate('安装税率');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('brand', '品牌');
            $filter->like('brand_set', '品牌系列');
            $filter->equal('floor','楼层');
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
        $show = new Show(Device::findOrFail($id));

        $show->id('ID');
        $show->brand('品牌');
        $show->brand_set('品牌系列');
        $show->dload('载重(kg)');
        $show->speedup('速度(m/s)');
        $show->floor('楼层');
        $show->hoisting_height('标准提升高度(m)');
        $show->freeboard_dev('设备超米费（元/米）');
        $show->freeboard_ins('安装超米费（元/米）');
        $show->device_price('设备单价');
        $show->device_rate('设备税率');
        $show->install_price('安装单价');
        $show->install_rate('安装税率');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Device);
        $form->text('brand', '品牌');
        $form->text('brand_set', '品牌系列');
        $form->text('dload', '载重(kg)');
        $form->number('speedup', '速度(m/s)');
        $form->number('floor', '楼层');
        $form->number('hoisting_height', '标准提升高度(m)');
        $form->number('freeboard_dev', '设备超米费（元/米）');
        $form->number('freeboard_ins', '安装超米费（元/米）');
        $form->number('device_price', '设备单价');
        $form->number('device_rate', '设备税率');
        $form->number('install_price', '安装单价');
        $form->number('install_rate', '安装税率');

        return $form;
    }
    public function brands(){
        $res=Device::select('brand')->groupBy('brand')->get();
        $arr=[];
        foreach($res as $d){
            array_push($arr,$d->brand);
        }
        return $arr;
    }
    public function brandsDetail(){
        $res=Device::select(['id','brand','brand_set','dload','floor'])->where('brand',Input::get('q'))
            ->get();
        $arr=[];
        foreach($res as $d){
            array_push($arr,
                ['id'=>$d->id,'text'=>'ID:'.implode('|',json_decode(json_encode($d), true))]
            );
        }
        return $arr;
    }
}
