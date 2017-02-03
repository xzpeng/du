<!--#include file="md532.asp"-->
<!--#include file="config.asp"-->
<%
UserId=request("P_UserId")
OrderId=request("P_OrderId")
CardId=request("P_CardId")
CardPass=request("P_CardPass")
FaceValue=formatnumber(request("P_FaceValue"),-1,true,false,false)
ChannelId=request("P_ChannelId")

subject=request("P_Subject")
description=request("P_Description") 
price=request("P_Price")
quantity=request("P_Quantity")
notic=request("P_Notic")
ErrCode=request("P_ErrCode")
PostKey=request("P_PostKey")
payMoney=request("P_PayMoney")

preEncodeStr=UserId&"|"&OrderId&"|"&CardId&"|"&CardPass&"|"&FaceValue&"|"&ChannelId&"|"&SalfStr

encodeStr=md5(preEncodeStr)

if PostKey=encodeStr then
	if ErrCode="0" then'支付成功
		response.write "errCode=0"
		'设置为成功订单,主意订单的重复处理
	else
		'支付失败
		response.write "err"
	end if
else
	response.write "数据被传改"
end if
%>