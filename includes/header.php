<link rel="stylesheet" href="../common/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../common/index_chart.css" type="text/css" media="screen,projection" />
<link rel="SHORTCUT ICON" href="http://www.mvland.com/favicon.ico" type="image/x-icon">

<?
	if($_SERVER['REQUEST_URI'] == '/') { ?>
		<script type="text/javascript">
        window.onload = function() {
            document.getElementById('search_box').focus();
        }
        </script> <?
	}
?>
    
</head>
 
<body>

	<div id="wrapper">
	
		<div id="header">
								
			<table border="0" width="100%">
				<tr>
					<td width="15%">
					<div style="padding-top: 5px;">
						<a href="http://www.mvland.com/"><img src="http://www.mvland.com/images/mvland_logo.gif" border="0" /></a>
					</div>
					</td>
					<td width="15%">
					<div style="text-align: left; padding-top: 30px;">
						<img src="http://www.mvland.com/images/slogan4.gif" border="0" />
					</div>
					</td>
					<td width="70%">
						<table align="right" border="0" width="100%">
                            <tr>
                                <td>
                                    <div style="padding-top: 5px; text-align: right;">
                                    <? 	
                                        if(!isset($_SESSION['user_id'])) { ?>
                                            <a href="http://www.mvland.com/signup">會員註冊</a> | <?	
                                            if(isset($song_url)) { ?>		
                                                <a href="http://www.mvland.com/login?next=<?=$song_url?>">登入</a> <?  } 
                                            else { ?>
                                                <a href="http://www.mvland.com/login">登入</a> <?  
                                            }
                                        } 
                                        else {
                                            $user_name = $_SESSION['user_name']; ?>
                                            歡迎, <a href="http://www.mvland.com/member/user=<?=$user_name?>"><?=$user_name?></a> | <a href="http://www.mvland.com/my_account">帳號</a> | <a href="http://www.mvland.com/provide">上傳</a> | <a href="http://www.mvland.com/logout">登出</a><?
                                        }
                                    ?>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <form action="http://www.mvland.com/search" method="get">
                                        <input type="text" name="mv_search" <? if(isset($_GET['search_submit'])) { $mv_search = $_GET['mv_search']; echo "value=\"$mv_search\""; } ?> id="search_box" /><input type="submit" name="search_submit" class="search_botton" value="MV搜尋">
                                    </form>
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
			</table>
			
		</div>
			
		<div class="menu">
			
		<ul id="nav">
			<li><a href="http://www.mvland.com/index?sort=sort_date">最新提供</a></li>
			<li><a href="http://www.mvland.com/index?sort=sort_views">最多瀏覽</a></li>
			<li><a href="http://www.mvland.com/index?sort=sort_comments">最多留言</a></li>
			<li><a href="http://www.mvland.com/index?sort=sort_favorites">最多最愛</a></li>				
		</ul>
			
		<br class="clear" />
			
	</div>
		
	<div id="sub_wrapper">
