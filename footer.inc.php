<br class="clear" />
<br/>
<br/>

</div><!-- content -->

</div><!-- wrapper -->

<div class="footer">

<hr class="clear" />

<div id="icon_list">
    <br/>
    <br/>
    <br/>
    <br/>

	<table id = "icon" width="800">

                            <tr>
                            	<th>Information</th>
                                <th>Legal</th>
                                <th>Let's be Friends</th>
                                <th>Download our Apps</th>
                            </tr> 
                            <tr>
                                <td><a href="#">About Us</a></td>
                                <td><a href="#">Terms of Use</a></td>
                                <td><a href="#">Facebook</a></td>
                                <td><a href="#">On the App Store</a></td>
                            </tr>
                            <tr>
                                <td><a href="#">How it works</a></td>
                                <td><a href="#">Privacy</a></td>
                                <td><a href="#">Twitter</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><a href="" onclick="openwin()">Contact Us</a></td>
                                <td><a href="#">Restaurant Owners</a></td>
                                <td><a href="#">Blog (Coming soon)</a></td>
                                <td><a href="#">Get it on Google</a></td>
                            </tr>

        </table>

</div>

<div id="copyright"><p style="color:white;">&copy; NextInLine, Mallblock LLC, 2014</p></div>

</div>

</body>
        <script src="js/jquery.js"></script>
        <script src="js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/jqueryEasing.js"></script>
	<script src="js/slides.min.jquery.js"></script>
        <script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
        <script src="js/select.js"></script>

        <script>           
        $(window).ready(function(){
            
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            $(".next").click(function(){
                    if(animating) return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();

                    //activate next step on progressbar using the index of next_fs
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show(); 
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                            step: function(now, mx) {
                                    //as the opacity of current_fs reduces to 0 - stored in "now"
                                    //1. scale current_fs down to 80%
                                    scale = 1 - (1 - now) * 0.2;
                                    //2. bring next_fs from the right(50%)
                                    left = (now * 50)+"%";
                                    //3. increase opacity of next_fs to 1 as it moves in
                                    opacity = 1 - now;
                                    current_fs.css({'transform': 'scale('+scale+')'});
                                    next_fs.css({'left': left, 'opacity': opacity});
                            }, 
                            duration: 800, 
                            complete: function(){
                                    current_fs.hide();
                                    animating = false;
                            }, 
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                    });
            });

            $(".previous").click(function(){
                    if(animating) return false;
                    animating = true;

                    current_fs = $(this).parent();
                    previous_fs = $(this).parent().prev();

                    //de-activate current step on progressbar
                    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                    //show the previous fieldset
                    previous_fs.show(); 
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                            step: function(now, mx) {
                                    //as the opacity of current_fs reduces to 0 - stored in "now"
                                    //1. scale previous_fs from 80% to 100%
                                    scale = 0.8 + (1 - now) * 0.2;
                                    //2. take current_fs to the right(50%) - from 0%
                                    left = ((1-now) * 50)+"%";
                                    //3. increase opacity of previous_fs to 1 as it moves in
                                    opacity = 1 - now;
                                    current_fs.css({'left': left});
                                    previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                            }, 
                            duration: 800, 
                            complete: function(){
                                    current_fs.hide();
                                    animating = false;
                            }, 
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                    });
            });

            $(".submit").click(function(){
                    return false;
            });                
            
                        $(window).resize(function(){
                                $('.slide,.slideshow .slides_container').css({
                                        'width':$('.slideshow').width(),
                                        'height':$('.slideshow').height()
                                });
                        });

                        $(window).resize();

                        $('.slideshow').slides({
                                generateNextPrev: true,
                                play:6000,
                                pause:1,
                                animationStart: function(){
                                        clearTimeout(timer);
                                        $('.slide-context').find('h1,h2').removeClass('effect');
                                },
                                animationComplete: function(){
                                        $('.slide-context').find('h1').addClass('effect');
                                        timer = setTimeout(function(){
                                                $('.slide-context').find('h2').addClass('effect');
                                        },400);
                                },
                                slidesLoaded: function() {
                                        $('.slide-context').find('h1').addClass('effect');
                                        timer = setTimeout(function(){
                                                $('.slide-context').find('h2').addClass('effect');
                                        },400);
                                }
                        });

                        $('.say').css({
                                'width':$('.testimonials .slides_container').width(),
                        })

                        $('.testimonials').slides({
                                effect:'fade',
                                generatePagination: false,
                                play:5000,
                                pause:1,
                                autoHeight:true
                        })

                });            
            
            function printPage(id)
            {
               var html="<html>";
               html+= document.getElementById(id).innerHTML;
               html+="</html>";

               var printWin = window.open('','','left=0,top=0,width=970,height=400,toolbar=0,scrollbars=0,status  =0');
               printWin.document.write(html);
               printWin.document.close();
               printWin.focus();
               printWin.print();
               printWin.close();
            }
            
            $(function() 
            {   
                $('label').click(function () {
                   $('label').removeClass('selected');
                   $(this).addClass('selected');
                });                
                
                var logic = function( currentDateTime ){
                  // 'this' is jquery object datetimepicker
                  if( $('#datepicker').val().substr(0,3) === "Sat" || $('#datepicker').val().substr(0,3) === "Sun"){
                    this.setOptions({
                      minTime:'11:00',
                      maxTime:'21:00'
                    });
                  }else
                    this.setOptions({
                      minTime:'8:00',
                      maxTime:'22:00'
                    });
                };
                
                var object = $('.dim,.box');
                $('#showBox').click(function(){
                object.show();
                });
                $('.dim,.close').click(function(){
                object.hide();
                });             
            
                //datetimepicker
                $('#datepicker').datetimepicker({
                timepicker:false,
                format:'l: m/d/Y',
                minDate:0
                });

                $('#timepicker').datetimepicker({
                datepicker:false,
                format:'H:i',
                onChangeDateTime:logic,
                onShow:logic
                }); 
                
                $('#sidemenu a').on('click', function(e){
                  e.preventDefault();

                  if($(this).hasClass('open')) {
                    // do nothing because the link is already open
                  } else {
                    var oldcontent = $('#sidemenu a.open').attr('href');
                    var newcontent = $(this).attr('href');

                    $(oldcontent).fadeOut('fast', function(){
                      $(newcontent).fadeIn().removeClass('hidden');
                      $(oldcontent).addClass('hidden');
                    });


                    $('#sidemenu a').removeClass('open');
                    $(this).addClass('open');
                  }
                });
                
                var overlay = $('.overlay');
                var login = $('.login');
                var body = $('body');

                $('.login-button').click(function(e){
                    e.preventDefault();
                    body.css({'overflow':'hidden'});
                    overlay.fadeIn(500,function(){
                        login.fadeIn(500);
                    })
                });

                $('.close').click(function(e){
                    e.preventDefault();
                    login.fadeOut(500,function(){
                        overlay.fadeOut(500);
                        body.css({'overflow':'visible'});
                    })
                });                
                        
            });
        </script>

</html>