<?php
$priceArr=[];
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
                <th>No.</th>
                <th>数量</th>
                <th>提升高度</th>
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
            <?php foreach($pj->elevator as $i=>$ele):?>
            <tr class="warning">
                <td>No.{{$ele->id}}</td>
                <td>{{$ele->num}}部</td>
                <td>{{$ele->height}}</td>
                <td>{{$ele->layer_number}}</td>
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

            <!--//电梯设备价START-->
            <tr>
                <td></td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td width="100"><span class="label label-primary">设备价</span></td>
                            <td>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>品牌</th>
                                        <th>载重</th>
                                        <th>速度</th>
                                        <th>楼层</th>
                                        <th>标准提升高度</th>
                                        <th>品牌系列</th>
                                        <th>设备超米费</th>
                                        <th>安装超米费</th>
                                        <th>设备税率</th>
                                        <th>安装单价</th>
                                        <th>设备单价</th>
                                        <th>安装税率</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $device=$ele->deviceAll;?>
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
                                        <td>{{$device->device_rate}}</td>
                                        <td>{{$device->install_price}}</td>
                                        <td>{{$device->device_price}}</td>
                                        <td>{{$device->install_rate}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                        $priceArr[$i]['device_price']=$device->device_price*$ele->num;
                        $priceArr[$i]['install_price']=$device->install_price*$ele->num;
                        ?>
                        <tr class="success">
                            <td class="text-right">计：</td>
                            <td>
                                <span class="label label-default">设备价：￥<?php print number_format($priceArr[$i]['device_price'],2);?>元</span>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                <span class="label label-default">安装价：￥<?php print number_format($priceArr[$i]['install_price'],2);?>元</span>
                                <span class="glyphicon glyphicon-pause rote90" aria-hidden="true"></span>
                                <span class="label label-info">￥<?php print number_format($priceArr[$i]['device_price']+$priceArr[$i]['install_price'],2);?>元</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--//电梯设备价END-->

            <!--//电梯功能价START-->
            <tr>
                <td></td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td width="100"><span class="label label-primary">功能价</span></td>
                            <td>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>功能ID</th>
                                        <th>功能名称</th>
                                        <th>功能加价</th>
                                        <th>是否标配/是否在基础价格包含</th>
                                        <th>功能描述</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(empty($priceArr[$i]['func'])){
                                        $priceArr[$i]['func']=0;
                                    }
                                    foreach($ele->func as $func):
                                        if($func->has_in_base!=1){
                                            $priceArr[$i]['func']+=$func->price;
                                        }
                                    ?>
                                    <tr>
                                        <td>{{$func->id}}</td>
                                        <td>{{$func->name}}</td>
                                        <td>{{$func->price}} 元/{{$func->unit}}</td>
                                        <td>{{$func->has_in_base}}</td>
                                        <td>{{$func->desc}}</td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                        $priceArr[$i]['func']=$priceArr[$i]['func']*$ele->num;
                        ?>
                        <tr class="success">
                            <td class="text-right">计：</td>
                            <td>
                                <span class="label label-default">功能价：￥<?php print number_format($priceArr[$i]['func'],2);?>元</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--//电梯功能价END-->

            <!--//电梯装修价START-->
            <tr>
                <td></td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td width="100"><span class="label label-primary">装修价</span></td>
                            <td>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>装修ID</th>
                                        <th>装修名称</th>
                                        <th>装修材料</th>
                                        <th>规格编号</th>
                                        <th>装修加价</th>
                                        <th>是否标配/是否在基础价格包含</th>
                                        <th>装修描述</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(empty($priceArr[$i]['fitment'])){
                                        $priceArr[$i]['fitment']=0;
                                    }
                                    foreach($ele->fitment as $fitment):
                                    if($fitment->has_in_base!=1){
                                        $priceArr[$i]['fitment']+=$fitment->price;
                                    }
                                    ?>
                                    <tr>
                                        <td>{{$fitment->id}}</td>
                                        <td>{{$fitment->name}}</td>
                                        <td>{{$fitment->stuff}}</td>
                                        <td>{{$fitment->spec}}</td>
                                        <td>{{$fitment->price}} 元/{{$fitment->unit}}</td>
                                        <td>{{$fitment->has_in_base}}</td>
                                        <td>{{$fitment->desc}}</td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                        $priceArr[$i]['fitment']=$priceArr[$i]['fitment']*$ele->num;
                        ?>
                        <tr class="success">
                            <td class="text-right">计：</td>
                            <td>
                                <span class="label label-default">功能价：￥<?php print number_format($priceArr[$i]['fitment'],2);?>元</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--//电梯装修价END-->

            <!--//电梯运费价START-->
            <?php
            $freight=\App\Models\DeviceFreight::where([
                'did'=>$ele->did,
                'to_province'=>$pj->province_id,
                'to_city'=>$pj->city_id,
            ])->first();
            if(empty($priceArr[$i]['freight'])){
                $priceArr[$i]['freight']=0;
            }
            ?>
            <tr>
                <td></td>
                <td colspan="100">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td width="100"><span class="label label-primary">运费价</span></td>
                            <td>
                                <?php if($freight):
                                $priceArr[$i]['freight']=$freight->price*$ele->num;
                                ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4">
                                        <address>
                                            <strong>发货地</strong><br>
                                            {{$freight->from_province}}/{{$freight->from_city}}
                                        </address>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <address>
                                            <strong>收获地</strong><br>
                                            {{$freight->to_province}}/{{$freight->to_city}}
                                        </address>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <address>
                                            <strong>运费单价</strong><br>
                                            {{$freight->price}}
                                        </address>
                                    </div>
                                </div>
                                <?php else:?>
                                <div class="alert alert-danger" role="alert">缺少运费价</div>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php if($freight):?>
                        <tr class="success">
                            <td class="text-right">计：</td>
                            <td>
                                <span class="label label-default">功能价：￥<?php print number_format($priceArr[$i]['freight'],2);?>元</span>
                            </td>
                        </tr>
                        <?php endif;?>
                    </table>
                </td>

            </tr>
            <!--//电梯运费价END-->

            <tr class="success">
                <td></td>
                <td colspan="100">
                    <span class="glyphicon glyphicon-yen" aria-hidden="true"></span>
                    <span class="label label-default">设备价：￥<?php print number_format($priceArr[$i]['device_price'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">安装价：￥<?php print number_format($priceArr[$i]['install_price'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">功能价：￥<?php print number_format($priceArr[$i]['func'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">装修价：￥<?php print number_format($priceArr[$i]['fitment'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">运费价：￥<?php print number_format($priceArr[$i]['freight'],2);?>元</span>
                    <span class="glyphicon glyphicon-pause rote90" aria-hidden="true"></span>
                    <span class="label label-info">￥
                        <?php
                            print number_format($priceArr[$i]['device_price']
                                    +$priceArr[$i]['install_price']
                                    +$priceArr[$i]['func']
                                    +$priceArr[$i]['fitment']
                                    +$priceArr[$i]['freight']

                                    ,2);
                        ?>
                        元
                    </span>
                </td>
            </tr>
            <?php endforeach;?>
            <?php
            $priceCount=[];
            foreach($priceArr as $rp){
                foreach($rp as $key=>$price){
                    if(empty($priceCount[$key])){
                        $priceCount[$key]=0;
                    }
                    $priceCount[$key]+=$price;
                }
            }
            ?>
            <tr class="danger">
                <td><span class="glyphicon glyphicon-yen" aria-hidden="true"></span></td>
                <td colspan="100" class="text-right">
                    <span class="label label-default">设备价：￥<?php print number_format($priceCount['device_price'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">安装价：￥<?php print number_format($priceCount['install_price'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">功能价：￥<?php print number_format($priceCount['func'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">装修价：￥<?php print number_format($priceCount['fitment'],2);?>元</span>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="label label-default">运费价：￥<?php print number_format($priceCount['freight'],2);?>元</span>
                    <span class="glyphicon glyphicon-pause rote90" aria-hidden="true"></span>
                    <span class="label label-info">￥
                        <?php
                        print number_format($priceCount['device_price']
                                +$priceCount['install_price']
                                +$priceCount['func']
                                +$priceCount['fitment']
                                +$priceCount['freight']
                                ,2);
                        ?>
                        元
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>