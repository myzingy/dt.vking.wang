<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\DeviceFunc;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DeviceFuncController extends Controller
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
//        DB::connection()->enableQueryLog();  // 开启QueryLog
//        $querystr=json_decode('{"brand_set":["\u8fc5\u8fbe||5\u7cfb\u5c0f\u673a\u623f",null],"dload":["1000\uff081050\uff09",null],"speedup":[null]}',true);
//
//        foreach($querystr as &$row){
//            $row=Arr::where($row, function ($value, $key) {
//                return !is_null($value);
//            });
//        }
//        var_dump('<pre>',$querystr);
//        $ds=Device::whereIn(DB::raw("CONCAT(brand,'||',brand_set)"),$querystr['brand_set']);
//        if($querystr['dload']){
//            $ds->whereIn('dload',$querystr['dload']);
//        }
//        if($querystr['speedup']){
//            $ds->whereIn('speedup',$querystr['speedup']);
//        }
//        $ds->get();
//        var_dump(DB::getQueryLog(),$ds->count());
//        exit;
        return $content
            ->header('电梯功能价')
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
            ->header('电梯功能价')
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
            ->header('电梯功能价')
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
            ->header('电梯功能价')
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
        $grid = new Grid(new DeviceFunc);

        $grid->id('ID');

        $grid->querystr('电梯设备')->display(function ($querystr) {
            if(!$querystr) return;
            $html='';
            foreach($querystr as $row){
                foreach($row as $label){
                    if($label){
                        $html.='<span class="label label-info">'.$label.'</span>';
                    }
                }
            }
            return $html;
        });
        $grid->name('功能名称');
        $grid->price('功能加价')->display(function($price){
            return number_format($price,2).' 元/'.$this->unit;
        });
        $grid->has_in_base('是否标配/是否在基础价格包含')->display(function($has_in_base){
            return $has_in_base==1?'已包含':'未包含';
        });
        $grid->desc('功能描述');

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '功能名称');
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
        $show = new Show(DeviceFunc::findOrFail($id));

        $show->id('ID');
        $show->device('电梯设备')->as(function ($device) {
            return 'ID:'.implode('|',json_decode(json_encode($device), true));
        });
        $show->name('功能名称');
        $show->price('功能加价');
        $show->has_in_base('是否标配/是否在基础价格包含');
        $show->unit('功能单位');
        $show->desc('功能描述');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($hasEdit=false)
    {
        $form = new Form(new DeviceFunc);

//        if($hasEdit){
//            $form->display('device','已选电梯设备')->with(function ($value) {
//                return 'ID:'.implode('|',json_decode(json_encode($value), true));
//            });
//        }
//        $form->select('_brand','电梯品牌')->options('/admin/device/brands')
//            ->load('did', '/admin/device/brandsDetail');
//        $form->multipleSelect('did','电梯设备');
//        $form->divide();

        $form->text('name','功能名称')->required();
        $form->currency('price','功能加价')->symbol('￥');
        $states = [
            'on'  => ['value' => 1, 'text' => '包含', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '不包含', 'color' => 'danger'],
        ];
        $form->switch('has_in_base','是否标配/是否在基础价格包含')->states($states);
        $form->radio('unit','功能单位')->options(DeviceFunc::UNIT)->required();
        $form->text('desc','功能描述');
        $form->divide();

        $dsArr=[];
        foreach(Device::groupBy('brand','brand_set')->get() as $d){
            $dsArr[$d->brand.'||'.$d->brand_set]=$d->brand.'/'.$d->brand_set;
        }
        $form->checkbox('querystr.brand_set','品牌系列')->options($dsArr);
        $dsArr=[];
        foreach(Device::groupBy('dload')->get() as $d){
            $dsArr[$d->dload]=$d->dload;
        }
        $form->checkbox('querystr.dload','载重')->options($dsArr);
        $dsArr=[];
        foreach(Device::groupBy('speedup')->get() as $d){
            $dsArr[$d->speedup]=$d->speedup;
        }
        $form->checkbox('querystr.speedup','速度')->options($dsArr);


        //忽略字段
        $form->ignore(['brand_set','dload','speedup']);
        $form->saving(function (Form $form){
            $form->model()->querystr=$form->querystr;
        });
        $form->saved(function (Form $form){
            if($fid=$form->model()->id){
                DB::table('device_func_rela')->where(['fid'=>$fid])->delete();
            }
            $querystr=$form->querystr;
            foreach($querystr as &$row){
                $row=Arr::where($row, function ($value, $key) {
                    return !is_null($value);
                });
            }
            $ds=Device::whereIn(DB::raw("CONCAT(brand,'||',brand_set)"),$querystr['brand_set']);
            if($querystr['dload']){
                $ds->whereIn('dload',$querystr['dload']);
            }
            if($querystr['speedup']){
                $ds->whereIn('speedup',$querystr['speedup']);
            }
            $real=[];
            foreach($ds->get() as $d){
                array_push($real,[
                    'fid'=>$fid,
                    'did'=>$d->id
                ]);
            };
            if($real){
                DB::table('device_func_rela')->insert($real);
            }
        });
        return $form;
    }
}
