<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Freight;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class FreightController extends Controller
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
            ->header('电梯运费价')
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
        $grid = new Grid(new Freight);

        $grid->id('ID');
        $grid->from('发货地点');
        $grid->to('到货地点');
        $grid->price('单台价格')->money();
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
        $grid->floor('楼层')->display(function ($floor) {
            $html='';
            foreach($floor as $dl){
                $html.="<div class='label label-default'>$dl</div> ";
            }
            return $html;
        });
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('brand', '品牌');
            $filter->like('brand_set', '品牌系列');
            $filter->like('dload', '载重');
            $filter->where(function ($query) {
                $query->whereRaw("find_in_set('{$this->input}',`floor`)");
            }, '楼层');
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
        $show = new Show(Freight::findOrFail($id));

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
        $form = new Form(new Freight);

        $form->distpicker(['_province1', 'from','_district1'], '发货城市');
        $form->distpicker(['_province2', 'to','_district2'], '收货城市');
        $form->currency('price','单台价格')->symbol('￥');

        $form->divide();

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
        foreach(Device::groupBy('floor')->get() as $d){
            $dsArr[$d->floor]=$d->floor;
        }
        $form->multipleSelect('floor','层数')->options($dsArr)->required();

        //忽略字段
        $form->ignore(['_province1','_district1','_province2','_district2']);

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
        return $form;
    }
}
