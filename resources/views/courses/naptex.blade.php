@extends('layouts.app')


@section('style')

@endsection
@section('content')
    @inject('sys', 'App\Http\Controllers\SystemController')
    <div class="md-card-content">
        <div style="text-align: center;display: none" class="uk-alert uk-alert-success" data-uk-alert="">

        </div>



        <div style="text-align: center;display: none" class="uk-alert uk-alert-danger" data-uk-alert="">

        </div>

        @if (count($errors) > 0)


            <div class="uk-alert uk-alert-danger  uk-alert-close" style="background-color: red;color: white" data-uk-alert="">

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!!$error  !!} </li>
                    @endforeach
                </ul>
            </div>

        @endif


    </div>

    <div style="">
        <div class="uk-margin-bottom" style="margin-left:900px" >


            <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="printTable">Print Table</a>
            <!--  <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="">Import from Excel</a>
             -->
            <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                <button class="md-btn md-btn-small md-btn-success"> columns <i class="uk-icon-caret-down"></i></button>
                <div class="uk-dropdown">
                    <ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
                </div>
            </div>





            <div style="margin-top: -5px" class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                <button class="md-btn md-btn-small md-btn-success uk-margin-small-top">Export <i class="uk-icon-caret-down"></i></button>
                <div class="uk-dropdown">
                    <ul class="uk-nav uk-nav-dropdown">
                        <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'csv',escape:'false'});"><img src='{!! url("public/assets/icons/csv.png")!!}' width="24"/> CSV</a></li>

                        <li class="uk-nav-divider"></li>
                        <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'excel',escape:'false'});"><img src='{!! url("public/assets/icons/xls.png")!!}' width="24"/> XLS</a></li>
                        <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'doc',escape:'false'});"><img src='{!! url("public/assets/icons/word.png")!!}' width="24"/> Word</a></li>
                        <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'powerpoint',escape:'false'});"><img src='{!! url("public/assets/icons/ppt.png")!!}' width="24"/> PowerPoint</a></li>
                        <li class="uk-nav-divider"></li>

                    </ul>
                </div>
            </div>




            <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>
            <a  href="{{url('/report/sms')}}"  onclick="return confirm('This will send bulk grades score notification to all students')"  title="sent bulk admission notification to applicants"> <i   title="click to sent bulk admission notification to applicants"  class="material-icons md-36 uk-text-success"   >phonelink_ring</i></a>

            <a href="{{url('/broadsheet/naptex')}}" ><i   title="refresh this page" class="uk-icon-refresh uk-icon-medium "></i></a>



        </div>
    </div>

    <div class="uk-width-xLarge-1-1">
        <div class="md-card">
            <div class="md-card-content">

                <form action="{{url('broadsheet/naptex')}}"  method="POST" accept-charset="utf-8"  >
                    {!!  csrf_field()  !!}
                    <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                {!! Form::select('program',
                            ($program ),
                              old("program",""),
                                ['class' => 'md-input parents','id'=>"parents",'required'=>'','placeholder'=>'select program']  )  !!}
                            </div>
                        </div>
                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                {!! Form::select('level',
                            ( $level ),
                              old("level",""),
                                ['class' => 'md-input parents','required'=>'','id'=>"parents",'placeholder'=>'select level'] )  !!}
                            </div>
                        </div>

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">

                                {!!  Form::select('semester', array('1'=>'1st sem','2'=>'2nd sem','3' => '3rd sem'), null, ['placeholder' => 'select semester','id'=>'parents','class'=>'md-input parents','required'=>''],old("semester","")); !!}

                            </div>
                        </div>

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                {!! Form::select('year',
                          (['' => 'Select year'] +$year ),
                            old("year",""),
                              ['class' => 'md-input parenst','id'=>"parents" ,'required'=>''] )  !!}   </div>
                        </div>

                        <!--                         <div class="uk-width-medium-1-5">
                                                    <div class="uk-margin-small-top">
                                                        <input type="text" style=" "   name="search"  class="md-input" placeholder="search by course name or course code">
                                                    </div>
                                                </div>-->







                    </div>
                    <div  align='center'>

                        <button class="md-btn  md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button>

                    </div>

                </form>
            </div>
        </div>
    </div>

    @if(Request::isMethod('post'))
        <p></p>
        <h4 class="heading_c"><center>Broadsheet for {{$sys->getProgram($programs) }} {{$years}}, Semester-{{$term}}  Level {{$levels}}</center></h4>
        <p></p>
        <div class="uk-width-xLarge-1-1">
            <div class="md-card">
                <div class="md-card-content" style="overflow: scroll">
                    <div class="uk-overflow-container" id='print'>

                        <table border='1' class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th colspan="3" style="text-align: center">SEM 1</th>
                                <th colspan="3" style="text-align: center">SEM 2</th>
                                <th colspan="3" style="text-align: center">SEM 3</th>
                                <th colspan="3" style="text-align: center">SEM 4</th>
                                <th colspan="3" style="text-align: center">SEM 5</th>
                                <th colspan="3" style="text-align: center">SEM 6</th>
                                <th colspan="" style="text-align: center">CUMMULATIVE</th>

                            </tr>
                            <tr>
                                <th class="filter-false remove sorter-false"  >N<u>O</u></th>
                                <th class="filter-false remove sorter-false"  >CANDIDATE N<u>O</u></th>
                                <th>NAME</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CR</th>
                                <th>GP</th>
                                <th>GPA</th>
                                <th>CGPA</th>


                                <th>REC.AWARD</th>
                                <th>REMARKS - (TRAILING COURSES)</th>

                            </tr>
                            </thead>
                            <tbody>



                            <?php

                            $count=0;
                            $mark=array();
                            ?>


                            @foreach($student as $stud=> $pupil)  <?php  $count++;?>
                            <tr>
                                <td><?php echo $count;?></td>
                                <td><?php $students[]=$pupil->indexno;
                                echo $pupil->indexno;
                                    \Session::put('students', $students);
                                    ?></td>
                                <td> {{  strtoupper(@$pupil->student->NAME)  	 }}</td>


                                <td><?php $cr1[]=$sys->totalCredit($pupil->indexno,'1','100H');
                                 echo    $sys->totalCredit($pupil->indexno,'1','100H');
                                    ?></td>
                                <td><?php $gp1[]=$sys->totalGradePoint($pupil->indexno,'1','100H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'1','100H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'1','100H');
                                    ?></td>
                                <td><?php $cr2[]= $sys->totalCredit($pupil->indexno,'2','100H');
                                    echo    $sys->totalCredit($pupil->indexno,'2','100H');
                                    ?></td>
                                <td><?php $gp2[]=$sys->totalGradePoint($pupil->indexno,'2','100H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'2','100H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'2','100H');
                                    ?></td>
                                <td><?php $cr3[]=$sys->totalCredit($pupil->indexno,'1','200H');
                                    echo    $sys->totalCredit($pupil->indexno,'1','200H');
                                    ?></td>
                                <td><?php $gp3[]=$sys->totalGradePoint($pupil->indexno,'1','200H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'1','200H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'1','200H');
                                    ?></td>
                                <td><?php $cr4[]=$sys->totalCredit($pupil->indexno,'2','200H');
                                    echo    $sys->totalCredit($pupil->indexno,'2','200H');
                                    ?></td>
                                <td><?php $gp4[]=$sys->totalGradePoint($pupil->indexno,'2','200H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'2','200H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'2','200H');
                                    ?></td>

                                <td><?php $cr5[]=$sys->totalCredit($pupil->indexno,'1','300H');
                                    echo    $sys->totalCredit($pupil->indexno,'1','300H');
                                    ?></td>
                                <td><?php $gp5[]=$sys->totalGradePoint($pupil->indexno,'1','300H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'1','300H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'1','300H');
                                    ?></td>

                                <td><?php $cr6[]=$sys->totalCredit($pupil->indexno,'2','300H');
                                    echo    $sys->totalCredit($pupil->indexno,'2','300H');
                                    ?></td>
                                <td><?php $gp6[]= $sys->totalGradePoint($pupil->indexno,'2','300H');
                                    echo    $sys->totalGradePoint($pupil->indexno,'2','300H');
                                    ?></td>
                                <td><?php
                                    echo    @$sys->totalGPA($pupil->indexno,'2','300H');
                                    ?></td>

                                <td><?php $cgpa=@number_format( (array_sum($gp1)+array_sum($gp2)+array_sum($gp3)+array_sum($gp4)+array_sum($gp5)+array_sum($gp6))/(array_sum($cr1)+array_sum($cr2)+array_sum($cr3)+array_sum($cr4)+array_sum($cr5)+array_sum($cr6)), 2, '.', '');echo $cgpa;

                                    ?></td>
                                <td><?php echo $sys->getClass($cgpa);?></td>
                                <td><?php print_r( $sys->checkTrails($pupil->indexno));?></td>
                            </tr>


                            @endforeach









                            </tbody>

                        </table>




                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection
@section('js')

    <script type="text/javascript">



    </script>
    <script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
    <script>
        $(document).ready(function () {
            $('select').select2({width: "resolve"});
        });</script>



@endsection