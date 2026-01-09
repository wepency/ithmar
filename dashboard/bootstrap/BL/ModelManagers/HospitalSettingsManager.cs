using PharmacyAndStock;
using PharmacyAndStock.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;


namespace PharmacyAndStock.BL.ModelManagers
{

    public class HospitalSettingsManager : Repository<HospitalMainSetting>
    {
        HospitalEntities DB;
        public HospitalSettingsManager(HospitalEntities db):base (db)
        {
            DB = db;
        }
      
     

    }
}