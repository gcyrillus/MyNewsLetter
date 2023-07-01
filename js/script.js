let markup;
document.addEventListener('DOMContentLoaded', (event) => { 
 	initTiny('.tinyeditor');
	let buttons = document.querySelectorAll('#cancel, #save, #saveNews');
	[...buttons].forEach((buttons) => {   buttons.style.display='none';   });
	markup = document.querySelector('#view').innerHTML;
});

function editn() {
	initTiny('.click2edit');
	document.querySelector('#editNews').innerHTML = document.querySelector('#view').innerHTML;
	let showButtons = document.querySelectorAll('#cancel, #save');
	[...showButtons].forEach((showButtons) => {showButtons.style.display='inline-block';   });
	
};
function saven() {
	removeTiny('.click2edit');
	document.querySelector('#editNews').innerHTML = document.querySelector('#view').innerHTML;
	let hideButtons = document.querySelectorAll('#cancel, #save');
	[...hideButtons].forEach((hideButtons) => {hideButtons.style.display='none';   });
	document.querySelector('#saveNews').style.display="inline-block";	
};

function canceln() {
	removeTiny('.click2edit');
	document.querySelector('#view').innerHTML = markup;
	let hideButtons = document.querySelectorAll('#cancel, #save');
	[...hideButtons].forEach((hideButtons) => {hideButtons.style.display='none';   });
	document.querySelector('#saveNews').style.display="inline-block";
};
function updateTXT() {	
	canceln();
	document.querySelector('#view').innerHTML=oldNewsFiles[document.querySelector('#loadOtherNewsFile').value];
	editn();
	document.querySelector('#saveNews').style.display="none";
}

function initTiny(target) {
	tinymce.init({
		convert_urls: false,
		selector: target, 
		browser_spellcheck: true,
		contextmenu: false,
		plugins: [
			'advlist', 'lists', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
			'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
			'insertdatetime', 'media', 'table', 'help', 'wordcount'
		], 		
		toolbar: 'undo redo | blocks | fontfamily | ' +
		'bold italic backcolor forecolor | alignleft aligncenter ' +
		'alignright alignjustify | bullist numlist outdent indent | table ' +
		'removeformat | help code preview fullscreen',
		advlist_bullet_styles: 'square',
		advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman', 		
		language_url: '../../plugins/plxMyNewsLetter/js/langsTiny/'+document.documentElement.lang+'.js', 
		language: document.documentElement.lang, 
	});
	
}

function removeTiny(target) {
	tinymce.remove(target);
}