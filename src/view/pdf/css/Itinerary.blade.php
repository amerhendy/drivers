<?php
$colwidth=$data['tablewidth']/12;
?>
<style>
.maindiv{
	border-bottom:1px dashed black;
	page-break-inside:avoid;
	border-spacing:1mm;
	width:190mm;
}
table
	{
		align:center;
	    font-size:11.0pt;
        width:{{$data['tablewidth']}}mm;
	}
.borderbottom{
    border-bottom: 1px solid black;
}
.wborder{
    border: 1px solid black;
}
.border_dashed{
    border-bottom: 1px dashed black;
}
.border_top{
    border-top: 1px dashed black;
}
.border_left{
    border-left: 1px dashed black;
}
.fullLeft{
    text-align: left;
}
.fullRight{
    text-align: right;
}
.fullCenter{
    text-align: center;
}
@php
$steps=[0.5,1,1.5,2,2.5,3,4,5,6,7,8,9,10,11,12,3.5,4.5,5.5,6.5,7.5,8.5,9.5,10.5,11.5];
for($i=0;$i<=count($steps)-1;$i++){
    print ".col".$steps[$i]."{
        width:".$colwidth*$steps[$i]."mm;
    }";
}
$fontsize=[9,10,11,12,13,14,15,16,17,18,19,20];
for($i=0;$i<=count($fontsize)-1;$i++){
    print ".FS".$fontsize[$i]."{
        font-size:$fontsize[$i];
    }";
}

@endphp
th.ednm{
    font-size:10.0pt;
}

 td.titlteTd{
    height:20mm;
    border-left:none;
    border-bottom:1px solid black;
    border-right:none;
    padding:0cm 5.4pt 0cm 5.4pt;
    text-align:center;
    direction:rtl;
    unicode-bidi:embed;
    font-size:11.0pt;
    align-content:center;
    }

 td.titlteTdEN{
    height:20mm;
    border-left:none;
    border-bottom:1px solid black;
    border-right:none;
    padding:0cm 5.4pt 0cm 5.4pt;
    text-align:center;
    direction:rtl;
    unicode-bidi:embed;
    font-size:9.0pt;
    align-content:center;
    }
 td.imgTd{
    border-bottom:1px solid black;
    border-right:none;
    padding:5mm 5mm 5mm 5mm;
    align-content:right;
 }
 img.logoImg{
	vertical-align:middle;
    width:20mm;
    align:center;
 }
 .tdTitleText{
    vertical-align:top;
    border-top:none;
    padding:0cm;
	font-size:12;
	width:30mm;
 }
 .fullname{
    vertical-align:top;
    border-bottom:1px dashed gray;
    padding:0cm;
	font-size:11;
	width:120mm;
 }

 .Job{
    vertical-align:top;
    border-bottom:1px dashed gray;
    padding:0cm;
	font-size:11;
	width:120mm;
 }
 .uid{
    border-bottom:1px dashed gray;
    vertical-align:top;
    border-top:none;
    padding:0cm;
	font-size:10;
	width:30mm;
 }
 .nid{
    border-bottom:1px dashed gray;
    vertical-align:top;
    border-top:none;
    padding:0cm;
	font-size:10;
	width:60mm;
 }

	td.rowspan5{
	width:30mm;
    border-left:0px  dashed black;
 }
 span.rowspancontent{
	width:20mm;
    writing-mode:horizontal-tb;
    rotate:45deg;
    text-orientation:upright;
    border:2px solid red;
 }
 .rotate90 {
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>
