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
        $grid->device_price('设备单价')->money();
        $grid->device_rate('设备税率');
        $grid->install_price('安装单价')->money();
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
        $form->currency('freeboard_dev', '设备超米费（元/米）')->symbol('￥');
        $form->currency('freeboard_ins', '安装超米费（元/米）')->symbol('￥');
        $form->currency('device_price', '设备单价')->symbol('￥');
        $form->currency('install_price', '安装单价')->symbol('￥');
        $form->slider('device_rate', '设备税率')->options(['max' => 0.17, 'min' => 0, 'step' => 0.01]);
        $form->slider('install_rate', '安装税率')->options(['max' => 0.11, 'min' => 0, 'step' => 0.01]);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });
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
    public function options($type){
        $keyword=Input::get('q');
        switch($type){
            case 'dload':
                $where=[
                    'brand'=>'',
                    'brand_set'=>'',
                ];
                list($where['brand'],$where['brand_set'])=explode('/',$keyword);
                $res=Device::select('dload')->where($where)->groupBy('dload')->get();
                break;
            case 'speedup':
                $where=[
                    'brand'=>'',
                    'brand_set'=>'',
                    'dload'=>'',
                ];
                list($brand,$where['dload'])=explode('@',$keyword);
                list($where['brand'],$where['brand_set'])=explode('/',$brand);
                $res=Device::select('speedup')->where($where)->groupBy('speedup')->get();
                break;
            case 'floor':
                $where=[
                    'brand'=>'',
                    'brand_set'=>'',
                    'dload'=>'',
                    'speedup'=>'',
                ];
                list($brand,$where['dload'],$where['speedup'])=explode('@',$keyword);
                list($where['brand'],$where['brand_set'])=explode('/',$brand);
                $res=Device::select('floor')->where($where)->groupBy('floor')->get();
                break;
        }
        $arr=[];
        foreach($res as $d){
            array_push($arr,['id'=>$keyword.'@'.$d->$type,'text'=>$d->$type]);
        }
        return $arr;
    }
}
