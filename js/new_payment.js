//-----------------------------------------------------  
 //Funcao: MascaraMoeda  
 //Sinopse: Mascara de preenchimento de moeda  
 //Parametro:  
 //   objTextBox : Objeto (TextBox)  
 //   SeparadorMilesimo : Caracter separador de milésimos  
 //   SeparadorDecimal : Caracter separador de decimais  
 //   e : Evento  
 //Retorno: Booleano  
 //Autor: Gabriel Fróes - www.codigofonte.com.br  
 // 
 // Alteração: Alteração para a permissão de pagar o conteúdo do componente.
 // Autor: Bruno Lins Alves - www.brunolinsalves.com
 //-----------------------------------------------------
 function mascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){  
    var sep = 0;  
    var key = '';  
    var i = j = 0;  
    var len = len2 = 0;  
    var strCheck = '0123456789';  
    var aux = aux2 = '';  
    var whichCode = (window.Event) ? e.which : e.keyCode;  
    if (whichCode == 13 || whichCode == 8) return true;  
    key = String.fromCharCode(whichCode); // Valor para o código da Chave  
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida  
    len = objTextBox.value.length;  
    for(i = 0; i < len; i++)  
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;  
    aux = '';  
    for(; i < len; i++)  
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);  
    aux += key;  
    len = aux.length;  
    if (len == 0) objTextBox.value = '';  
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;  
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;  
    if (len > 2) {  
        aux2 = '';  
        for (j = 0, i = len - 3; i >= 0; i--) {  
            if (j == 3) {  
                aux2 += SeparadorMilesimo;  
                j = 0;  
            }  
            aux2 += aux.charAt(i);  
            j++;  
        }  
        objTextBox.value = '';  
        len2 = aux2.length;  
        for (i = len2 - 1; i >= 0; i--)  
        objTextBox.value += aux2.charAt(i);  
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);  
    }  
    return false;  
}

function dateCardExpirationMask(inputData, e) {
    if (document.all) // Internet Explorer
        var tecla = event.keyCode;
    else //Outros Browsers
        var tecla = e.which;
    
    if (tecla >= 47 && tecla < 58) { // numeros de 0 a 9 e "/"
        var data = inputData.value;
        if (data.length == 2){
            data += '/';
            inputData.value = data;
        }
    } else if (tecla == 8 || tecla == 0) // Backspace, Delete e setas direcionais(para mover o cursor, apenas para FF)
        return true;
    else
        return false;
}

function somenteNumeros(e) {
    var charCode = e.charCode ? e.charCode : e.keyCode;
    // charCode 8 = backspace   
    // charCode 9 = tab
    if (charCode != 8 && charCode != 9) {
        // charCode 48 equivale a 0   
        // charCode 57 equivale a 9
        if (charCode < 48 || charCode > 57) {
            return false;
        }
    }
}

function mascaraNumeroCartao(o, f) {
    v_obj= o;
    v_fun= f;
    setTimeout("execmascaraNumeroCartao()", 1);
}
function execmascaraNumeroCartao() {
    v_obj.value = v_fun(v_obj.value);
}
function mcc(v) {
    v=v.replace(/\D/g, "");
    v=v.replace(/^(\d{4})(\d)/g, "$1 $2");
    v=v.replace(/^(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3");
    v=v.replace(/^(\d{4})\s(\d{4})\s(\d{4})(\d)/g, "$1 $2 $3 $4");
    return v;
}

window.onload = function() {
	document.getElementById('card_number').onkeypress = function(){
		mascaraNumeroCartao(this, mcc);
	}
}

