'use strict';
var _lang;

    // se cargan los lang a memoria, asi los query serán mas rapidos 
 cargar_lang();
 

async function cargar_lang(){
  if( _lang==undefined){
        await iniciarDB();
        const query = await db1.language.where({id_fila: 1}).first();
       _lang = query!=undefined ? query:{}
  }
 
}
     function lang(line, id = '')
	{
         let tem =_lang[line];
        line =tem!=undefined?tem :"";        
		/*if (id != '')
		{
			line = '<label for="'+id+'">'+line+"</label>";
		}*/
		return line;
	}
