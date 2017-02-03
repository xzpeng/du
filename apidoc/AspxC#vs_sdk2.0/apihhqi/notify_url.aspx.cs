using System;
using System.Collections;
using System.Configuration;
using System.Data;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using Apihhqi;

public partial class notify_url : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string UserId = apihhqi.Trim(Request["P_UserId"]);
        string OrderId = apihhqi.Trim(Request["P_OrderId"]);
        string CardId = apihhqi.Trim(Request["P_CardId"]);
        string CardPass = apihhqi.Trim(Request["P_CardPass"]);
        string FaceValue = apihhqi.Trim(Request["P_FaceValue"]);
        string ChannelId = apihhqi.Trim(Request["P_ChannelId"]);

        string subject = apihhqi.Trim(Request["P_Subject"]);
        string description = apihhqi.Trim(Request["P_Description"]);
        string price = apihhqi.Trim(Request["P_Price"]);
        string quantity = apihhqi.Trim(Request["P_Quantity"]);
        string notic = apihhqi.Trim(Request["P_Notic"]);
        string ErrCode = apihhqi.Trim(Request["P_ErrCode"]);
        string PostKey = apihhqi.Trim(Request["P_PostKey"]);
        string payMoney = apihhqi.Trim(Request["P_PayMoney"]);

        string SalfStr = ConfigurationManager.AppSettings["SalfStr"];
        string preEncodeStr = UserId + "|" + OrderId + "|" + CardId + "|" + CardPass + "|" + FaceValue + "|" + ChannelId + "|" + SalfStr;

        string encodeStr = apihhqi.GetMD5(preEncodeStr, "gb2312");

        //Response.Write(preEncodeStr + "<br>" + encodeStr+"<br>");//调试语句

        if (PostKey.CompareTo(encodeStr) == 0)
        {
            Response.Write("errCode=0");//表示数据合法
            if (int.Parse(ErrCode) == 0)//说明是充值成功了的
            {
                //这里进行订单更新
                Response.Write("充值成功");
            }
        }
        else
        {
            Response.Write("数据不合法");
        }
    }
}
