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
</style>
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
                        <td>{{$func->pivot->num}}{{$func->unit}}</td>
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
                        <td>{{$fitment->pivot->num}}{{$fitment->unit}}</td>
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
                        <td><?php print number_format($ele->expe->设备税率计算*$ele->num,2);?></td>
                    </tr>
                </table>
            </td>
            <td width="120">
                <button type="button" class="btn <?php print $ele->status<2?'btn-primary':'btn-link'?>" data-toggle="modal" data-target="#exampleModal">
                    <?php print $ele->status<2?'审核设备':'修改审核设备'?>
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="_act" value="device" />
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">税率</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="设备税率" class="form-control" id="inputEmail3" placeholder="0 到 0.17 之间的数字" value="<?php print $ele->expe->设备税率?$ele->expe->设备税率:$device->device_rate?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">非标单价</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="设备非标单价" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->设备非标单价?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">临时用梯费</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="设备临时用梯费" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->设备临时用梯费?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary btn-submit" data-action="device">审核通过</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
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
                        <td><?php print number_format($ele->expe->安装税率计算*$ele->num,2);?></td>
                    </tr>
                </table>
            </td>
            <?php if($ele->status<2):?>
            <td width="100">
                请先审核设备
            </td>
            <?php else:?>
            <td width="100">
                <button type="button" class="btn <?php print $ele->status<3?'btn-primary':'btn-link'?>" data-toggle="modal" data-target="#exampleModalInstall">
                    <?php print $ele->status<3?'审核安装':'修改审核安装'?>
                </button>
                <div class="modal fade" id="exampleModalInstall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="_act" value="install" />
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">税率</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="安装税率" class="form-control" id="inputEmail3" placeholder="0 到 0.17 之间的数字" value="<?php print $ele->expe->安装税率?$ele->expe->安装税率:$device->install_rate?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">非标单价</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="安装非标单价" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->安装非标单价?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">临时用梯费</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="安装临时用梯费" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->安装临时用梯费?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">贯通门增加安装价</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="贯通门增加安装价" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->贯通门增加安装价?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">二次验收费用</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="二次验收费用" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->二次验收费用?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">政府验收费</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="政府验收费" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->政府验收费?>">
                                            <span id="helpBlock2" class="help-block">政府验收费.........</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">备注</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="desc" class="form-control" id="inputPassword3" placeholder="" value="<?php print $ele->expe->desc?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary btn-submit" data-action="device">审核通过</button>
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
                                    $ele->expe->设备税率计算*$ele->num
                                    +$ele->expe->安装税率计算*$ele->num
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
<script>
    $(function(){
        $('.btn-submit').click(function(){
            var action=$(this).attr('data-action');
            var $form=$(this).parents('.modal-footer').prev().find('form');
            $form.trigger('submit');
        })
    })
</script>