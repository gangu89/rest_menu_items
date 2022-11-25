var count = 0;
function Load_blog_data(total)
{
if((count+1)*5 >= total){
//$("ViewMoreBlog").css("display","none");

}
$.post("http://localhost/d9/web/load_ajax_data",{ 'action':'view_more_blog','count':count,'limit':'5'},function(data){
	$("#blog_display_article").append(data);
	count=count+1;
  });
}
