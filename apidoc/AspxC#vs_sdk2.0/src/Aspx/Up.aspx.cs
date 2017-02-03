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

public partial class Up : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        Init();
    }

    protected void Init()
    {
        string P_UserId = apihhqi.Trim(Request["userId"]);
        string P_CardId = apihhqi.Trim(Request["cardId"]);
        string P_CardPass = apihhqi.Trim(Request["cardPass"]);
        string P_FaceValue = apihhqi.Trim(Request["faceValue"]);
        string P_ChannelId = apihhqi.Trim(Request["channelId"]);
        string P_Subject = apihhqi.Trim(Request["subject"]);
        string P_Price = apihhqi.Trim(Request["price"]);
        string P_Quantity = apihhqi.Trim(Request["quantity"]);
        string P_Description = apihhqi.Trim(Request["description"]);
        string P_Notic = apihhqi.Trim(Request["notic"]);
        string P_Result_url = "http://"+Request.ServerVariables["http_host"]+"/"+ConfigurationManager.AppSettings["result_url"];
        string P_Notify_url = "http://" + Request.ServerVariables["http_host"] + "/" + ConfigurationManager.AppSettings["notify_url"];

        string P_OrderId = apihhqi.getOrderId();
        string SalfStr = ConfigurationManager.AppSettings["SalfStr"];
        string preEncodeStr = P_UserId+"|"+P_OrderId+"|"+P_CardId+"|"+P_CardPass+"|"+P_FaceValue+"|"+P_ChannelId+"|"+SalfStr;

        string P_PostKey = apihhqi.GetMD5(preEncodeStr, "gb2312");

        string ps="";
        ps += "P_UserId=" + P_UserId;
        ps += "&P_OrderId=" + P_OrderId;
        ps += "&P_CardId=" + P_CardId;
        ps += "&P_CardPass=" + P_CardPass;
        ps += "&P_FaceValue=" + P_FaceValue;
        ps += "&P_ChannelId=" + P_ChannelId;
        ps += "&P_Subject=" + P_Subject;
        ps += "&P_Price=" + P_Price;
        ps += "&P_Quantity=" + P_Quantity;
        ps += "&P_Description=" + P_Description;
        ps += "&P_Notic=" + P_Notic;
        ps += "&P_Result_url=" + P_Result_url;
        ps += "&P_Notify_url=" + P_Notify_url;
        ps += "&P_PostKey=" + P_PostKey;

        string gateway = ConfigurationManager.AppSettings["gateway"];

        Response.Redirect(gateway+"?"+ps);
    }
}
