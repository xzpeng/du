using System;
using System.Collections.Generic;
using System.Text;

namespace Apihhqi
{
    public class apihhqi
    {
        public apihhqi()
        {
        }
        public static string getOrderId()
        {
            Random ro = new Random(10);
            int a = ro.Next(1000000, 9999999);
            return a + getMyDate();
        }

        public static string Trim(string str)
        {
            return str == null ? "" : str.Trim();
        }

        public static string getMyDate()
        {
            return System.DateTime.Now.ToShortDateString().Replace("-", "") + System.DateTime.Now.ToShortTimeString().Replace(":", "");
        }

        public static string GetMD5(string dataStr, string codeType)
        {
            System.Security.Cryptography.MD5 md5 = new System.Security.Cryptography.MD5CryptoServiceProvider();
            byte[] t = md5.ComputeHash(System.Text.Encoding.GetEncoding(codeType).GetBytes(dataStr));
            System.Text.StringBuilder sb = new System.Text.StringBuilder(32);
            for (int i = 0; i < t.Length; i++)
            {
                sb.Append(t[i].ToString("x").PadLeft(2, '0'));
            }
            return sb.ToString();
        }
    }
}
