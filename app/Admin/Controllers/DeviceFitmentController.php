<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\DeviceFitment;
use App\Http\Controllers\Controller;
use App\Models\DeviceFunc;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
        $grid->name('装饰项目名称');
        $grid->stuff('材料');
        $grid->spec('规格编号');
        $grid->price('装修单价')->display(function($price){
            return number_format($price,2).' 元/'.$this->unit;
        });
        $grid->desc('描述');
        $grid->has_in_base('是否标配/是否在基础价格包含')->display(function($has_in_base){
            return $has_in_base==1?'已包含':'未包含';
        });
        $grid->querystr('设备条件')->display(function ($querystr) {
            if(!$querystr) return;
            $html='';
            foreach($querystr as $row){
                foreach($row as $label){
                    if($label){
                        $html.='<span class="label label-default">'.$label.'</span> ';
                    }
                }
            }
            return $html;
        });
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

        $form->text('name','装饰项目名称')->required();
        $form->text('stuff','材料');
        $form->text('spec','规格编号');
        $form->radio('unit','装修单位')->options(DeviceFunc::UNIT)->required();
        $form->currency('price','单价')->symbol('￥');
        $states = [
            'on'  => ['value' => 1, 'text' => '包含', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '不包含', 'color' => 'danger'],
        ];
        $form->switch('has_in_base','是否标配/是否在基础价格包含')->states($states);
        $form->text('desc','描述');
        $form->divide();

        $dsArr=[];
        foreach(Device::groupBy('brand')->get() as $d){
            $dsArr[$d->brand]=$d->brand;
        }
        $form->checkbox('querystr.brand','品牌')->options($dsArr);

        //忽略字段
        $form->ignore(['_brand']);
        $form->saving(function (Form $form){
            $form->model()->querystr=$form->querystr;
        });
        $form->saved(function (Form $form){
            if($fid=$form->model()->id){
                DB::table('device_fitment_rela')->where(['fid'=>$fid])->delete();
            }
            $querystr=$form->querystr;
            foreach($querystr as &$row){
                $row=Arr::where($row, function ($value, $key) {
                    return !is_null($value);
                });
            }
            $ds=Device::whereIn('brand',$querystr['brand']);
            $real=[];
            foreach($ds->get() as $d){
                array_push($real,[
                    'fid'=>$fid,
                    'did'=>$d->id
                ]);
            };
            if($real){
                DB::table('device_fitment_rela')->insert($real);
            }
        });
        return $form;
    }
}
