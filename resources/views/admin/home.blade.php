@extends('admin.layout')

@section('content')      
            <div class="content">
                
                <div class="page-header">
                    <div class="icon">
                        <span class="ico-arrow-right"></span>
                    </div>
                    <h1>Dashboard <small>METRO STYLE ADMIN PANEL</small></h1>
                </div>
                
                <div class="row-fluid">
                    <div class="span12">
                        <div class="widgets">
                            <div class="widget blue value">
                                <div class="left">60%</div>
                                <div class="right">
                                    $1,530 amount paid<br/>
                                    $2,102 in queue<br/>
                                    $11,100 total taxes
                                </div>
                                <div class="bottom">
                                    <a href="#">Invoices statistics</a>
                                </div>
                            </div>
                            <div class="widget green icon">
                                <div class="left">
                                    <div class="icon">
                                        <span class="ico-download"></span>
                                    </div>
                                </div>
                                <div class="right">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td>HDD</td><td>4.5 / 250 GB</td>
                                        </tr>
                                        <tr>
                                            <td>MySQL</td><td>1.8 / 10 GB</td>
                                        </tr>
                                        <tr>
                                            <td>Databases</td><td>1 / 5</td> 
                                        </tr>
                                        <tr>
                                            <td>E-mails</td><td>5 / 15</td> 
                                        </tr>
                                        <tr>
                                            <td>Tickets</td><td>2</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="bottom">
                                    <a href="#">Server information</a>
                                </div>
                            </div>
                            <div class="widget orange chart nmr">
                                <div class="left">
                                    <span class="mChartBar" sparkWidth="90" sparkHeight="90" sparkType="bar" sparkBarColor="#FFFFFF" sparkBarWidth="10">10,9,8.5,8,9,8,7,7.5</span>
                                </div>
                                <div class="right">
                                    65% New<br/>
                                    35% Returning<br/>
                                    00:05:12 Average time on site
                                </div>
                                <div class="bottom">
                                    <a href="#">List visits</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    
                    <div class="span7">
                        
                        <div class="block">
                            <div class="head">
                                <div class="icon"><span class="ico-chart-4"></span></div>
                                <h2>Visitor Statistics</h2>
                                <ul class="buttons">
                                    <li><a href="#" class="ublock"><div class="icon"><span class="ico-undo"></span></div></a></li>
                                    <li><a href="#" class="cblock"><div class="icon"><span class="ico-sort"></span></div></a></li>
                                </ul>
                            </div>
                            <div class="data">
                                <div id="main_chart" style="height: 300px;"></div>                                
                            </div>       
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="stat">
                                        <span class="cdblue">2,350</span> PAGE<br/> VIEWS
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="stat">
                                        <span class="cblue">1,120</span> UNIQUE<br/> PAGE VIEWS
                                    </div>                                                                        
                                </div>
                                <div class="span4">
                                    <div class="stat">
                                        <span class="corange">720</span> SALES<br/> PER PERIOD
                                    </div>                                                
                                </div>               
                            </div>
                        </div>

                        <div class="block">
                            <div class="head orange">                                
                                <h2>Latest Orders</h2>
                                <ul class="buttons">
                                    <li><a href="#" onClick="source('table_main'); return false;"><div class="icon"><span class="ico-info"></span></div></a></li>
                                    <li><a href="#" class="ublock"><div class="icon"><span class="ico-undo"></span></div></a></li>
                                    <li><a href="#" class="cblock"><div class="icon"><span class="ico-sort"></span></div></a></li>
                                </ul>
                            </div>
                            <div class="data-fluid">
                                <table cellpadding="0" cellspacing="0" width="100%" class="table lcnp">
                                    <thead>
                                        <tr>
                                            <th width="16"><input type="checkbox" class="checkall"/></th>                                        
                                            <th>Name</th>
                                            <th>E-mail</th>
                                            <th>Phone</th>                       
                                            <th width="78">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>                                        
                                            <td>Dmitry</td>
                                            <td>dmitry@domain.com</td>
                                            <td>+98(765) 432-10-98</td>                      
                                            <td>
                                                <a href="#" class="button green">
                                                    <div class="icon"><span class="ico-pencil"></span></div>
                                                </a>
                                                <a href="#" class="button red">
                                                    <div class="icon"><span class="ico-remove"></span></div>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>                                        
                                            <td>Alex</td>
                                            <td>alex@domain.com</td>
                                            <td>+98(765) 432-10-99</td>                      
                                            <td>
                                                <a href="#" class="button green">
                                                    <div class="icon"><span class="ico-pencil"></span></div>
                                                </a>
                                                <a href="#" class="button red">
                                                    <div class="icon"><span class="ico-remove"></span></div>
                                                </a>                                              
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>
                                            <td>John</td>
                                            <td>john@domain.com</td>
                                            <td>+98(765) 432-10-97</td>                      
                                            <td>
                                                <a href="#" class="button green">
                                                    <div class="icon"><span class="ico-pencil"></span></div>
                                                </a>
                                                <a href="#" class="button red">
                                                    <div class="icon"><span class="ico-remove"></span></div>
                                                </a>                                              
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>                                        
                                            <td>Angelina</td>
                                            <td>angelina@domain.com</td>
                                            <td>+98(765) 432-10-90</td>                      
                                            <td>
                                                <a href="#" class="button green">
                                                    <div class="icon"><span class="ico-pencil"></span></div>
                                                </a>
                                                <a href="#" class="button red">
                                                    <div class="icon"><span class="ico-remove"></span></div>
                                                </a>                                              
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox"/></td>
                                            <td>Tom</td>
                                            <td>tom@domain.com</td>
                                            <td>+98(765) 432-10-92</td>                      
                                            <td>
                                                <a href="#" class="button green">
                                                    <div class="icon"><span class="ico-pencil"></span></div>
                                                </a>
                                                <a href="#" class="button red">
                                                    <div class="icon"><span class="ico-remove"></span></div>
                                                </a>                                                
                                            </td>                                        
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>                            
                        </div>                        
                        
                    </div>
                    <div class="span5">
                        <div class="block">
                            <div class="head">
                                <div class="icon"><span class="ico-tag"></span></div>
                                <h2>Tickets</h2>
                                <ul class="buttons">             
                                    <li><a href="#" onClick="source('tickets'); return false;"><div class="icon"><span class="ico-info"></span></div></a></li>
                                    <li><a href="#" class="ublock"><div class="icon"><span class="ico-undo"></span></div></a></li>
                                    <li><a href="#" class="cblock"><div class="icon"><span class="ico-sort"></span></div></a></li>
                                </ul>                                
                            </div>
                            <div class="data-fluid">
                                <table width="100%" class="table tickets">
                                    <tr>
                                        <td width="55" class="bl_blue"><span class="label label-info">new</span></td>
                                        <td width="50">#AA-325 <span class="mark">23/02/2013</span></td>
                                        <td><a href="#" class="cblue">Buy on themeforest this great template...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>
                                    <tr>
                                        <td class="bl_blue"><span class="label label-info">new</span></td>
                                        <td>#AA-216 <span class="mark">22/02/2013</span></td>
                                        <td><a href="#" class="cblue">Go to shop and buy beer!</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>                
                                    <tr>
                                        <td class="bl_green"><span class="label label-success">done</span></td>
                                        <td>#AC-857 <span class="mark">21/02/2013</span></td>
                                        <td><a href="#" class="cgreen">Buy on themeforest this great template...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>
                                    <tr>
                                        <td class="bl_red"><span class="label label-important">removed</span></td>
                                        <td>#VB-57 <span class="mark">20/02/2013</span></td>
                                        <td><a href="#" class="cred">Buy something for my dog...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>   
                                    <tr>
                                        <td class="bl_red"><span class="label label-important">removed</span></td>
                                        <td>#VB-44 <span class="mark">20/02/2013</span></td>
                                        <td><a href="#" class="cred">Buy something for my dog...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>
                                    
                                    <tr>
                                        <td class="bl_green"><span class="label label-success">done</span></td>
                                        <td>#AA-216 <span class="mark">22/02/2013</span></td>
                                        <td><a href="#" class="cgreen">Go to shop and buy beer!</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>                
                                    <tr>
                                        <td class="bl_red"><span class="label label-important">removed</span></td>
                                        <td>#VB-31 <span class="mark">21/02/2013</span></td>
                                        <td><a href="#" class="cred">Buy on themeforest this great template...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>
                                    <tr>
                                        <td class="bl_green"><span class="label label-success">done</span></td>
                                        <td>#VB-57 <span class="mark">20/02/2013</span></td>
                                        <td><a href="#" class="cgreen">Buy something for my dog...</a> <span class="mark">Added by Dmitry Ivaniuk</span></td>                                        
                                    </tr>                                      
                                </table>
                            </div>                                   
                        </div>                        
                        
                        <div class="block">
                            <div class="head dblue">                                
                                <h2>Messages</h2>
                                <ul class="buttons">             
                                    <li><a href="#" onClick="source('messages'); return false;"><div class="icon"><span class="ico-info"></span></div></a></li>
                                    <li><a href="#" class="ublock"><div class="icon"><span class="ico-undo"></span></div></a></li>
                                    <li><a href="#" class="cblock"><div class="icon"><span class="ico-sort"></span></div></a></li>
                                </ul>                                
                            </div>
                            <div class="data dark npr npb">                                
                                <div class="messages scroll" style="height: 200px;">
                                    <div class="item blue">
                                        <div class="arrow"></div>
                                        <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat.</div>
                                        <div class="date">09.02., 21:04</div>
                                    </div>
                                    <div class="item dblue out">
                                        <div class="arrow"></div>
                                        <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat.</div>
                                        <div class="date">09.02., 21:02</div>
                                    </div>                                    
                                    <div class="item blue">
                                        <div class="arrow"></div>
                                        <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat.</div>
                                        <div class="date">09.02., 20:55</div>
                                    </div>                                    
                                </div>                                
                            </div>    
                            <div class="toolbar dark">
                                <div class="input-prepend input-append">
                                    <span class="add-on dblue"><span class="icon-envelope icon-white"></span></span>
                                    <input type="text"/>                              
                                    <button class="btn dblue" type="button">Send  <span class="icon-arrow-next icon-white"></span></button>
                                </div>                                 
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div><!-- end content -->
@endsection
