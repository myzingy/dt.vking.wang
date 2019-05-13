<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Funtion;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class FuntionController extends Controller
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
            ->header('电梯功能价')
            ->description('新建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Funtion);

        $grid->id('ID');
        $grid->brand('品牌系列')->display(function ($brand) {
            $brand_set=implode(',',$this->brand_set);
            if(strstr($brand_set,'/')) return $brand_set;
            return $brand.'/'.$brand_set;
        });
        $grid->dload('载重')->display(function ($dload) {
            $html='';
            foreach($dload as $dl){
                $html.="<div class='label label-default'>$dl</div> ";
            }
            return $html;
        });
        $grid->speedup('速度')->display(function ($speedup) {
            $html='';
            foreach($speedup as $dl){
                $html.="<div class='label label-default'>$dl</div> ";
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
            $filter->like('brand', '品牌');
            $filter->like('brand_set', '品牌系列');
            $filter->like('dload', '载重');
            $filter->like('speedup', '速度');
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
        $show = new Show(Funtion::findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Funtion);

        $dsArr=[];
        foreach(Device::groupBy('brand','brand_set')->get() as $d){
            $dsArr[$d->brand.'/'.$d->brand_set]=$d->brand.'/'.$d->brand_set;
        }
        $form->multipleSelect('brand_set','品牌系列')->options($dsArr)->required();

        $dsArr=[];
        foreach(Device::groupBy('dload')->get() as $d){
            $dsArr[$d->dload]=$d->dload;
        }
        $form->multipleSelect('dload','载重')->options($dsArr)->required();
        $dsArr=[];
        foreach(Device::groupBy('speedup')->get() as $d){
            $dsArr[$d->speedup]=$d->speedup;
        }
        $form->multipleSelect('speedup','速度')->options($dsArr)->required();

        $form->text('name','功能名称')->required();
        $form->currency('price','功能加价')->symbol('￥');
        $states = [
            'on'  => ['value' => 1, 'text' => '包含', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '不包含', 'color' => 'danger'],
        ];
        $form->switch('has_in_base','是否标配/是否在基础价格包含')->states($states);
        $form->radio('unit','功能单位')->options(Funtion::UNIT)->required();
        $form->text('desc','功能描述');

        //忽略字段
        //$form->ignore(['_province1','_district1','_province2','_district2']);

        $form->saving(function (Form $form){
            $brand=[];
            $brand_set=[];
            foreach($form->brand_set as $bs){
                if($bs){
                    $bs=explode('/',$bs);
                    $brand[$bs[0]]=$bs[0];
                    $brand_set[]=$bs[1];
                }
            }
            if($brand) $form->model()->brand=implode(',',$brand);
        });

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
}
