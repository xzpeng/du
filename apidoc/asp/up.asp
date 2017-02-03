<!--#include file="md532.asp"-->
<!--#include file="config.asp"-->
<%
P_UserId=request("userId")
P_CardId=request("cardId")
P_CardPass=request("cardPass")
P_FaceValue=request("faceValue")
P_ChannelId=request("channelId")
P_Subject=request("subject")
P_Price=request("price")
P_Quantity=request("quantity")
P_Description=request("description")
P_Notic=request("notic")
P_Result_url=result_url
P_Notify_url=notify_url

P_OrderId=getOrderId()
preEncodeStr=P_UserId&"|"&P_OrderId&"|"&P_CardId&"|"&P_CardPass&"|"&P_FaceValue&"|"&P_ChannelId&"|"&SalfStr

P_PostKey=md5(preEncodeStr)


params="P_UserId="&P_UserId
params=params&"&P_OrderId="&P_OrderId
params=params&"&P_CardId="&P_CardId
params=params&"&P_CardPass="&P_CardPass
params=params&"&P_FaceValue="&P_FaceValue
params=params&"&P_ChannelId="&P_ChannelId
params=params&"&P_Subject="&P_Subject
params=params&"&P_Price="&P_Price
params=params&"&P_Quantity="&P_Quantity
params=params&"&P_Description="&P_Description
params=params&"&P_Notic="&P_Notic
params=params&"&P_Result_url="&P_Result_url
params=params&"&P_Notify_url="&P_Notify_url
params=params&"&P_PostKey="&P_PostKey


'在这里对订单进行入库保存

'下面这句是提交到API
response.Redirect(gateWary&"?"&params)

function getOrderId()
	Randomize
	MyValue = Int((9999999-1000000+1) * Rnd + 1000000)
	strDate=getMyDate
	h=hour(now)
	m=minute(now)
	s=second(now)
	if h<10 then h="0"&h
	if m<10 then m="0"&m
	if s<10 then s="0"&s
	getOrderId=strDate&h&m&s&MyValue
end function

'获取日期串如：20100604
function getMyDate()
	y=year(date)
	m=month(date)
	d=day(date)
	
	if m<10 then m="0"&m
	if d<10 then d="0"&d
	
	getMyDate=y&m&d
end function
%>