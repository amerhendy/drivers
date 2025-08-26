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
.tdTitleText{
    vertical-align:top;
    border-top:none;
    padding:0cm;
    text-justify:inter-character;
 }
.fsel{
    border-bottom: 1px solid red;
}
</style>
