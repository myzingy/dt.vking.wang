<?php
$priceArr=[];
$pj=$ele->project;
$i=0;
?>
<style>
    .project {
        background-color: #fefefe;;
    }
    .project address{
        border: 1px solid #ccc;
    }
    .rote90{
        transform:rotate(-90deg);
    }
    .flexbut{
        position: fixed;
        left: 50px;
        top: 35%;
        height: 100px;
        border-radius: 0 50%;
        z-index: 99;
    }
</style>
<script src="/js/jquery.form.js"></script>
<div class="project" id="project">
    <h1 class="text-center">{{$pj->name}}</h1>
    <div class="project container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目地点</strong><br>
                    {{$pj->province_id}}/{{$pj->city_id}}/{{$pj->district_id}} {{$pj->addr}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>甲方名称</strong><br>
                    {{$pj->first_party}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>技术负责人</strong><br>
                    {{$pj->artisan_man}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>成本负责人</strong><br>
                    {{$pj->price_man}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目定位</strong><br>
                    {{$pj->desc}}
                </address>
            </div>
            <div class="col-xs-12 col-sm-4">
                <address>
                    <strong>项目状态</strong><br>
                    {{$pj->status}}
                </address>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>电梯数量</th>
                <th>提升高度(m)</th>
                <th>层/站/门数</th>
                <th>停站标记</th>
                <th>基站楼层</th>
                <th>标准</th>
                <th>驱动方式</th>
                <th>控制方式</th>
                <th>消防返回开关在</th>
                <th>暂停服务开关在</th>
                <th>控制功能</th>
                <th>底坑深度mm</th>
                <th>顶层高度mm</th>
                <th>井道结构</th>
                <th>井道支架固定</th>
                <th>厅门尺寸（mm）</th>
                <th>轿厢尺寸（mm）</th>
                <th>备注</th>
            </tr>
        </thead>
        <tbody>

            <tr class="warning">
                <td>{{$ele->num}}部</td>
                <td>{{$ele->height}}</td>
                <td>{{$ele->layer_number}}层{{$ele->layer_number_site}}站{{$ele->layer_number_door}}门</td>
                <td>{{$ele->stop_sign}}</td>
                <td>{{$ele->base_floor}}</td>
                <td>{{$ele->standard}}</td>
                <td>{{$ele->drive_mode}}</td>
                <td>{{$ele->control_mode}}</td>
                <td>{{$ele->fire_switch}}</td>
                <td>{{$ele->stop_switch}}</td>
                <td>{{$ele->control_function}}</td>
                <td>{{$ele->pit_depth}}</td>
                <td>{{$ele->top_height}}</td>
                <td>{{$ele->well_structure}}</td>
                <td>{{$ele->well_fixation}}</td>
                <td>{{$ele->hall_width}}*{{$ele->hall_height}}</td>
                <td>{{$ele->car_width}}*{{$ele->car_height}}*{{$ele->car_depth}}</td>
                <td>{{$ele->desc}}</td>
            </tr>


        </tbody>
    </table>

    <!--//电梯设备价START-->
    <table class="table table-bordered table-condensed">
        <tr>
            <td>
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>设备ID</th>
                        <th>品牌</th>
                        <th>载重</th>
                        <th>速度</th>
                        <th>楼层</th>
                        <th>标准提升高度</th>
                        <th>品牌系列</th>
                        <th>设备超米费</th>
                        <th>安装超米费</th>
                        <th>设备单价</th>
                        <th>设备税率</th>
                        <th>安装单价</th>
                        <th>安装税率</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $device=$ele->device;?>
                    <tr>
                        <td>{{$device->id}}</td>
                        <td>{{$device->brand}}</td>
                        <td>{{$device->dload}}</td>
                        <td>{{$device->speedup}}</td>
                        <td>{{$device->floor}}</td>
                        <td>{{$device->hoisting_height}}</td>
                        <td>{{$device->brand_set}}</td>
                        <td>{{$device->freeboard_dev}}</td>
                        <td>{{$device->freeboard_ins}}</td>
                        <td>{{$device->device_price}}</td>
                        <td>{{$device->device_rate}}</td>
                        <td>{{$device->install_price}}</td>
                        <td>{{$device->install_rate}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <!--//电梯设备价END-->

    <!--//电梯功能价START-->
    <table class="table table-bordered table-condensed">
        <tr>
            <td width="60"><span class="label label-primary">功能</span></td>
            <td>
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="100">功能ID</th>
                        <th width="100">功能名称</th>
                        <th width="100">功能加价</th>
                        <th width="100">功能数量</th>
                        <th >是否标配/是否在基础价格包含</th>
                        <th>功能描述</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($ele->func as $func):
                    ?>
                    <tr>
                        <td>{{$func->id}}</td>
                        <td>{{$func->name}}</td>
                        <td>{{$func->price}} 元/{{$func->unit}}</td>
                        <td>{{$func->num}}{{$func->unit}}</td>
                        <td>{{$func->has_in_base}}</td>
                        <td>{{$func->desc}}</td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <!--//电梯功能价END-->

    <!--//电梯装修价START-->
    <table class="table table-bordered table-condensed">
        <tr>
            <td width="60"><span class="label label-primary">装修</span></td>
            <td>
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th width="100">装修ID</th>
                        <th width="100">装修名称</th>
                        <th width="100">装修加价</th>
                        <th width="100">装修数量</th>
                        <th width="100">装修材料</th>
                        <th width="100">规格编号</th>
                        <th>是否标配/是否在基础价格包含</th>
                        <th>装修描述</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($ele->fitment as $fitment):
                    ?>
                    <tr>
                        <td>{{$fitment->id}}</td>
                        <td>{{$fitment->name}}</td>
                        <td>{{$fitment->price}} 元/{{$fitment->unit}}</td>
                        <td>{{$fitment->num}}{{$fitment->unit}}</td>
                        <td>{{$fitment->stuff}}</td>
                        <td>{{$fitment->spec}}</td>
                        <td>{{$fitment->has_in_base}}</td>
                        <td>{{$fitment->desc}}</td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <!--//电梯装修价END-->

    <!--//电梯运费价START-->
    <table class="table table-bordered table-condensed">
        <tr>
            <td width="60"><span class="label label-primary">运费</span></td>
            <td>
                <?php if($ele->expe->设备运输费>0):?>
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <address>
                            <strong>运费单价</strong><br>
                            {{$ele->expe->设备运输费}}
                        </address>
                    </div>
                </div>
                <?php else:?>
                <div class="alert alert-danger" role="alert">缺少运费价</div>
                <?php endif;?>
            </td>
        </tr>
    </table>
    <!--//电梯运费价END-->
    <table class="table table-bordered">
        <tr class="success">
            <td>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>设备基价</th>
                        <th width="100">功能加价</th>
                        <th width="100">装修选项</th>
                        <th width="100">运输费</th>
                        <th width="100">设备超米费</th>
                        <th width="100">非标单价</th>
                        <th width="120">临时用梯设备费</th>
                        <th width="100">税率</th>
                        <th width="100">设备单价</th>
                        <th width="100">设备合计</th>
                    </tr>
                    <tr>
                        <td><?php print number_format($ele->expe->设备基价,2);?></td>
                        <td><?php print number_format($ele->expe->设备功能加价,2);?></td>
                        <td><?php print number_format($ele->expe->设备装修选项,2);?></td>
                        <td><?php print number_format($ele->expe->设备运输费,2);?></td>
                        <td><?php print number_format($ele->expe->设备超米费,2);?></td>
                        <td><?php print number_format($ele->expe->设备非标单价,2);?></td>
                        <td><?php print number_format($ele->expe->设备临时用梯费,2);?></td>
                        <td><?php print number_format($ele->expe->设备税率,2);?></td>
                        <td><?php print number_format($ele->expe->设备税率计算,2);?></td>
                        <td><?php print number_format($ele->expe->设备合计,2);?></td>
                    </tr>
                </table>
            </td>
            <?php if($ele->status<1):?>
            <td width="100">
                未提审，请等待提审
            </td>
            <?php else:?>
            <td width="120">
                <span class="label label-info"><?php print \App\Models\Elevator::getStatusStr($ele->status)?></span>
                <?php if($ele->status<\App\Models\Elevator::STATUS_JD_JT || \Encore\Admin\Facades\Admin::user()->can('甲方集团')):?>
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal">
                    审核/修改设备
                </button>
                <?php endif;?>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">审核设备</h4>
                            </div>
                            <div class="modal-body">
                                <?php
                                //$form = new \Encore\Admin\Form(new \App\Models\ElevatorPrice);
                                $form = new \Encore\Admin\Form($ele->expe);

                                $form->hidden('_act')->default('device');
                                $form->hidden('_method')->default('put');

                                $form->number('设备税率')->default($ele->expe->设备税率)->min(0)->max(1)->help('例如，13%请输入0.13');
                                $form->currency('设备非标单价','非标单价')->symbol('￥')->default($ele->expe->设备非标单价);
                                $form->currency('设备临时用梯费','临时用梯费')->symbol('￥')->default($ele->expe->设备临时用梯费);
                                $form->largefile('设备非标文件','非标文件')->default($ele->expe->设备非标文件);
                                $form->html(function (){
                                    if($this->设备非标文件){
                                        return '<a href="'.(str_replace('//dt.','//dtfile.',url($this->设备非标文件))).'" target="_blank">非标文件: '.$this->设备非标文件.'</a>';
                                    }
                                }, $label = '');

                                $form->disableReset();
                                $form->disableEditingCheck();
                                $form->disableCreatingCheck();
                                $form->disableViewCheck();
                                $form->tools(function (\Encore\Admin\Form\Tools $tools) {
                                    $tools->disableDelete();
                                    $tools->disableView();
                                    $tools->disableList();
                                });
                                //$form->setWidth(7, 3);
                                $form->setAction('/admin/elevatorSuper/'.$ele->expe->id);

                                echo $form->render();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <?php endif;?>
        </tr>
        <tr class="success">
            <td>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>安装基价</th>
                        <th width="100">政府验收费</th>
                        <th width="100">贯通门增加安装价</th>
                        <th width="100">二次验收费用</th>
                        <th width="100">安装超米费</th>
                        <th width="100">非标单价</th>
                        <th width="120">临时用梯设备费</th>
                        <th width="100">税率</th>
                        <th width="100">安装单价</th>
                        <th width="100">安装合计</th>
                    </tr>
                    <tr>
                        <td><?php print number_format($ele->expe->安装基价,2);?></td>
                        <td><?php print number_format($ele->expe->政府验收费,2);?></td>
                        <td><?php print number_format($ele->expe->贯通门增加安装价,2);?></td>
                        <td><?php print number_format($ele->expe->二次验收费用,2);?></td>
                        <td><?php print number_format($ele->expe->安装超米费,2);?></td>
                        <td><?php print number_format($ele->expe->安装非标单价,2);?></td>
                        <td><?php print number_format($ele->expe->安装临时用梯费,2);?></td>
                        <td><?php print number_format($ele->expe->安装税率,2);?></td>
                        <td><?php print number_format($ele->expe->安装税率计算,2);?></td>
                        <td><?php print number_format($ele->expe->安装合计,2);?></td>
                    </tr>
                </table>
            </td>
            <?php if($ele->status<2):?>
            <td width="100">
                请先审核设备
            </td>
            <?php else:?>
            <td width="100">
                <span class="label label-info"><?php print \App\Models\Elevator::getStatusStr($ele->status)?></span>
                <?php if($ele->status<\App\Models\Elevator::STATUS_JD_JT || \Encore\Admin\Facades\Admin::user()->can('甲方集团')):?>
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModalInstall">
                    审核/修改安装
                </button>
                <?php endif;?>
                <div class="modal fade" id="exampleModalInstall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">审核安装</h4>
                            </div>
                            <div class="modal-body">
                                <?php
                                //$form = new \Encore\Admin\Form(new \App\Models\ElevatorPrice);
                                $form = new \Encore\Admin\Form($ele->expe);

                                $form->hidden('_act')->default('install');
                                $form->hidden('_method')->default('put');

                                $form->number('安装税率')->default($ele->expe->安装税率)->min(0)->max(1)->help('例如，13%请输入0.13');
                                $form->currency('安装非标单价','非标单价')->symbol('￥')->default($ele->expe->安装非标单价);
                                $form->currency('安装临时用梯费','临时用梯费')->symbol('￥')->default($ele->expe->安装临时用梯费);
                                $form->currency('贯通门增加安装价','贯通门增加安装价')->symbol('￥')->default($ele->expe->贯通门增加安装价);
                                $form->currency('二次验收费用','二次验收费用')->symbol('￥')->default($ele->expe->二次验收费用);
                                $form->text('政府验收费公式','政府验收费公式')->default($ele->expe->政府验收费公式);
                                $zf=\App\Models\DeviceYearly::where([
                                    'brand'=>$device->brand,
                                    'city_id'=>$pj->city_id
                                ])->first();
                                $form->html(function () use($zf){
                                    if($zf){
                                        print "<textarea disabled rows=5 style='width:100%;'>{$pj->city_id}:\n\n{$zf->explain}\n\n{$zf->desc}</textarea>";
                                    }else{
                                        print '未查到 政府验收费';
                                    }
                                });

                                $form->currency('政府验收费','政府验收费')->symbol('￥')->default($ele->expe->政府验收费);
                                $form->text('desc','备注')->default($ele->expe->desc);

                                $form->disableReset();
                                $form->disableEditingCheck();
                                $form->disableCreatingCheck();
                                $form->disableViewCheck();
                                $form->tools(function (\Encore\Admin\Form\Tools $tools) {
                                    $tools->disableDelete();
                                    $tools->disableView();
                                    $tools->disableList();
                                });
                                $form->setWidth(7, 3);
                                $form->setAction('/admin/elevatorSuper/'.$ele->expe->id);

                                echo $form->render();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <?php endif;?>
        </tr>
        <tr class="success">
            <td>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>备注</th>
                        <th width="100">合计</th>
                    </tr>
                    <tr>
                        <td><?php print $ele->expe->desc;?></td>
                        <td><?php
                            print number_format(
                                    $ele->expe->设备合计
                                    +$ele->expe->安装合计
                                    ,2);
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="120">

            </td>
        </tr>
    </table>
</div>
<button type="button" id="myButton"
        class="btn btn-success btn-lg flexbut" autocomplete="off">
    调整配置
</button>
<script>
    $(function(){
        $("form").submit(function(){
            $(this).ajaxSubmit({
                success:function(data){ //提交成功的回调函数
                    location.reload();
                }
            });
            return false; //不刷新页面
        });
        $('.btn-submit').click(function(){
            var action=$(this).attr('data-action');
            var $form=$(this).parents('.modal-footer').prev().find('form');
            $form.trigger('submit');
        })
        $('input[name="政府验收费公式"]').keyup(function(e){
            let val=0;
            let gs=$(this).val()
            gs=gs.replace('（','(').replace('）',')');
            $('input[name="政府验收费公式"]').val(gs);
            eval('val='+gs);
            console.log($(this).val(),val);
            $('input[name="政府验收费"]').val(val);
        })
        $('#myButton').on('click', function () {
            history.back()
        })
        setInterval(function(){
            var width=$('.main-sidebar').css('width');
            $('.flexbut').css({left:parseInt(width)+'px'});
        },300)
    })
</script>