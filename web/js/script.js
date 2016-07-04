var CenterX = 315;
var CenterY = 315;
var bigRadius = 250;
var colorRayAndCircleByLabel = '#48D1CC';
var colorLabel = '#36c';
var radiusLabel = 10;
var colorSelectLabel = "Red";
var shadowLabelSize = 10;
var shadowColor = "black";

/*
 * General functions
 * */

/**
 * Из декартовой в полярную систему координат.
 *
 * @param {float} x
 * @param {float} y
 * @returns {object}
 */
function cartesian2Polar(x, y) {
    var upX = (x-CenterX);
    var upY = (y-CenterY);
    distance = Math.sqrt(upX * upX + upY * upY);
    radians = Math.atan2(upY, upX);
    degr = radians*180/Math.PI+90;
    polarCoor = {distance: distance, degr: degr};
    return polarCoor;
}

/**
 * From polar in dec
 *
 * @param {float} radius
 * @param {float} degr
 * @returns {object}
 */
function cartesian2Dec(radius, degr) {
    radians = (degr-90)*(Math.PI/180);
    if(degr >= 0 && degr <= 180){
        tan  = Math.tan(radians);
        x = Math.sqrt((radius*radius)/(tan*tan+1));
        y = x*tan;
    }else{
        tan  = Math.tan(-radians);
        x = -Math.sqrt((radius*radius)/(tan*tan+1));
        y = -x*tan;
    }
    decCoor = {X: x+CenterX, Y: y+CenterY};
    return decCoor;
}

/**
*function for convert HEX -> rgba
 * */

function hexInArray(h){
    var m = h.slice(1).match(/.{2}/g);
    m[0]=parseInt(m[0], 16);
    m[1]=parseInt(m[1], 16);
    m[2]=parseInt(m[2], 16);
    return m;
};

function hexArrayInRgbString(m) {
    var rgb = 'rgb('+m[0]+', '+m[1]+', '+m[2]+')';
    return rgb;
}

function changeColorLayers(color,numLayers) {
    var arColor = hexInArray(color);
    var tempColor = arColor;
    var arRBA = [];
    var i = 0;
    var difColorRed = (256-arColor[0])/numLayers;
    var difColorGreen = (arColor[1])/(numLayers-1);
    var difColorBlue = (arColor[2])/(numLayers-1);
    var red = arColor[0] + difColorRed;
    var green = arColor[1];
    var blue = arColor[2];
    for(red; red <= 256; red = red + difColorRed){
        tempColor[0] = Math.floor(red);
        tempColor[1] = Math.floor(green);
        tempColor[2] = Math.floor(blue);
        arRBA[i] = hexArrayInRgbString(tempColor);
        green = green - difColorGreen;
        blue = blue - difColorBlue;
        i++;
    }
    console.log(arRBA);
    return arRBA;
}

/*
 * Block functions for sectors
 * */

function createSector(data) {
    var arColors = changeColorLayers(data.color,data.numLayers);
    var i;
    var difRadius = bigRadius/data.numLayers;
    var radius = bigRadius;

    $('canvas').drawArc({
        layer: true,
        name: 'mainArc'+data.id,
        strokeStyle: '#000',
        strokeWidth: 2,
        x: CenterX, y: CenterY,
        radius: bigRadius,
        start: data.beginAngle, end: data.endAngle,
    });

    for(i=1;i<=data.numLayers;i++){
        $('canvas').drawSlice({
            layer: true,
            name: 'slice'+data.id+i,
            groups: ['chart', 'slices'],
            fillStyle: arColors[i-1],
            x: CenterX, y: CenterY,
            start: data.beginAngle, end: data.endAngle,
            radius: radius,
            strokeStyle: '#f60',
            strokeWidth: 3,
            dblclick: function(layer) {
                var polar = cartesian2Polar(layer.eventX, layer.eventY);
                var link = $('#create_label_link').attr('href','create_note.html?circle_id='+data.circle_id+'&radius='+polar.distance/bigRadius+'&degr='+polar.degr);
                link.removeClass( "btn-primary" ).addClass( "btn-danger" );
                link.text('Добавить заметку в выбрнный сектор');
            },
            click: function(layer) {
                $('canvas').setLayer('mainArc'+data.id, {
                    shadowColor: shadowColor,
                    shadowBlur: 20
                })
                .drawLayers();
            },
            mouseout: function(layer) {
                $('canvas').setLayer('mainArc'+data.id, {
                    shadowBlur: 0
                })
                .drawLayers();
            }
        });
        radius = radius - difRadius;
    }
    $('canvas')
        .drawText({
            layer: true,
            fillStyle: '#c33',
            fontFamily: 'Trebuchet MS, sans-serif',
            fontSize: 18,
            text: data.name,
            x: CenterX, y: CenterY,
            radius: bigRadius+20,
            rotate: (data.beginAngle<data.endAngle)?(data.beginAngle+data.endAngle)/2:(data.beginAngle+data.endAngle+360)/2,
            dblclick: function(layer) {
                $('#pop_sector').css('display','block').attr('id',555);
            },
        });
}

/*
* Block functions for labels
* */

function rayAndCircleByLabel(layer,id) {
    var pol = cartesian2Polar(layer.x, layer.y);
    var dec = cartesian2Dec(bigRadius*2,pol.degr);
    $('canvas').drawArc({
        layer: true,
        strokeStyle: colorRayAndCircleByLabel,
        strokeWidth: 3,
        name: 'circleByLabel'+id,
        groups: ['circleByLabel'],
        x: CenterX, y: CenterY,
        radius: pol.distance,
    });
    $('canvas').drawLine({
        layer: true,
        strokeWidth: 3,
        name: 'lineByLabel'+id,
        groups: ['lineByLabel'],
        strokeStyle: colorRayAndCircleByLabel,
        x1: CenterX, y1: CenterY,
        x2: dec.X, y2: dec.Y,
    });
}

function createNamePopUpLabel(id,x,y,text) {
    var heightPopUp = 30;
    var widthPopUp = 200;
    $('canvas').drawRect({
        layer: true,
        fillStyle: 'white',
        strokeStyle: '#c33',
        strokeWidth: 4,
        name: 'nameLabelPopup'+id,
        groups: ['nameLabelPopup'],
        x: x + widthPopUp/2, y: y - heightPopUp/2 - 10,
        width: 200,
        height: 30,
        cornerRadius: 10
    });
    $('canvas').drawText({
        layer: true,
        name: 'nameLabelPopupText'+id,
        groups: ['nameLabelPopupText'],
        fillStyle: 'black',
        strokeWidth: 2,
        x: x + widthPopUp/2, y: y - heightPopUp/2 - 10,
        fontSize: '15pt',
        fontFamily: 'Verdana, sans-serif',
        text: text
    })
}

function delRayNamePopUpAndCircleByLabel(id) {
    $('canvas').removeLayer('circleByLabel'+id);
    $('canvas').removeLayer('lineByLabel'+id);
    $('canvas').removeLayer('nameLabelPopup'+id);
    $('canvas').removeLayer('nameLabelPopupText'+id);
}

function delRayNamePopUpAndCircleAllLabels() {
    $('canvas').removeLayerGroup('circleByLabel');
    $('canvas').removeLayerGroup('lineByLabel');
    $('canvas').removeLayerGroup('nameLabelPopup');
    $('canvas').removeLayerGroup('nameLabelPopupText');
}


function createLabel(data) {
    var LabelCoord = cartesian2Dec(data.radius*bigRadius, data.degr)
    $('canvas').drawArc({
        layer: true,
        draggable: true,
        name: 'myLabel'+data.id,
        fillStyle: colorLabel,
        x: LabelCoord.X, y: LabelCoord.Y,
        radius: radiusLabel,
        data: {'id' : data.id, 'name': data.name},
        shadowColor: shadowColor,
        shadowBlur: shadowLabelSize,
        dragstop: function(layer) {
            var pol = cartesian2Polar(layer.x, layer.y);
            var dec = cartesian2Dec(pol.distance,pol.degr);
            delRayNamePopUpAndCircleByLabel(layer.data.id);
        },
        drag: function(layer) {
            delRayNamePopUpAndCircleByLabel(layer.data.id);
            rayAndCircleByLabel(layer,layer.data.id);
        },
        mouseover: function(layer) {
            var Label = $('canvas').getLayer(layer.name);
            Label.fillStyle = colorSelectLabel;
            delRayNamePopUpAndCircleAllLabels();
            rayAndCircleByLabel(layer,layer.data.id);
            createNamePopUpLabel(layer.data.id,layer.x,layer.y,layer.data.name);
        },
        mouseout: function(layer) {
            var Label = $('canvas').getLayer(layer.name);
            Label.fillStyle = colorLabel;
            delRayNamePopUpAndCircleByLabel(layer.data.id);
        },
        dblclick: function(layer) {
            $('#pop_label_link').css('display','block').attr('href','list_notes.html?id='+layer.data.id);
        },
    });
}

/*
* Block with creating elements
* */

var numLayers = 4;

var dataSector1 = {
    id:1,
    numLayers:numLayers,
    color:'#8FBC8F',
    beginAngle:10,
    endAngle:90,
    name:'Example1',
    circle_id: 1,
};

var dataSector2 = {
    id:2,
    numLayers:numLayers,
    color:'#FFD700',
    beginAngle:90,
    endAngle:200,
    name:'Example2',
    circle_id: 1,
};
var dataSector3 = {
    id:3,
    numLayers:numLayers,
    color:'#BA55D3',
    beginAngle:200,
    endAngle:10,
    name:'Example3',
    circle_id: 1,
};

createSector(dataSector1);
createSector(dataSector2);
createSector(dataSector3);

var dataLabel1 = {
    id:1,
    radius:0.13,
    degr:56,
    name:'Note1'
};

var dataLabel2 = {
    id:2,
    radius:0.71,
    degr:230,
    name:'Note2'
};

$(document).ready(function() {
    $('canvas').triggerLayerEvent('myLabel1', 'mouseover');
    $('canvas').triggerLayerEvent('slice11', 'click');
});


createLabel(dataLabel1);
createLabel(dataLabel2);


/*
* block for creating sectors
* */

var countOfFields = 3; // Текущее число полей
var curFieldNameId = 3; // Уникальное значение для атрибута name
var maxFieldLimit = 12; // Максимальное число возможных полей
function deleteField(a) {
    //if (countOfFields > 0) {
    var arrInput = a.parentNode.getElementsByTagName('input');
    var name = arrInput[0].value;

//            BX.ajax.post(window.location.href, {delete_name : name} ,function(){});
    // Получаем доступ к ДИВу, содержащему поле
    var contDiv = a.parentNode;
    // Удаляем этот ДИВ из DOM-дерева
    contDiv.parentNode.removeChild(contDiv);
    // Уменьшаем значение текущего числа полей
    countOfFields--;
    //}
    // Возвращаем false, чтобы не было перехода по сслыке
    return false;
}
function addField() {
    // Проверяем, не достигло ли число полей максимума
    if (countOfFields >= maxFieldLimit) {
        alert("Число полей достигло своего максимума = " + maxFieldLimit);
        return false;
    }
    // Увеличиваем текущее значение числа полей
    countOfFields++;
    // Увеличиваем ID
    curFieldNameId++;
    // Создаем элемент ДИВ
    var div = document.createElement("div");
    div.setAttribute("class", "form-group create_sector");
    // Добавляем HTML-контент с пом. свойства innerHTML
    div.innerHTML =  "<input type=\"text\" placeholder=\"Название сектора\"  name=\"sector_name[" + curFieldNameId + "]\" class=\"form-control\" value=\"\" autocomplete=\"off\"/>" +
        "<input type=\"color\" placeholder=\"Цвет сектора\"  name=\"sector_color[" + curFieldNameId + "]\" class=\"form-control\" value=\"#FFFAFA\" autocomplete=\"off\"/>" +
        " <input type=\"button\" class=\"form-control\" onclick=\"return deleteField(this)\" href=\"#\" value=\"x\">";
    // Добавляем новый узел в конец списка полей
    document.getElementById("formCircleCreate").appendChild(div);
    // Возвращаем false, чтобы не было перехода по сслыке
    return false;
}