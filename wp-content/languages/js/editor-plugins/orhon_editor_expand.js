addEvent(window,'load',function(){
	if (tinymce.activeEditor.getBody().textContent) {
		tinymce.activeEditor.setContent(Unicode2M(tinymce.activeEditor.getContent()));
	}
	//tinymce.activeEditor.setContent(Unicode2M(tinymce.activeEditor.getContent()))
});
function addEvent(el,ev,f){
		if (window.addEventListener) {
			el.addEventListener(ev , f, false);
		}else {
			el.attachEvent("on" + ev , f );
		}
}