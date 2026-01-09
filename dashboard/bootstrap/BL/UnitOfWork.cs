using PharmacyAndStock.Models;
using PharmacyAndStock.BL.ModelManagers;
using System;
using System.Collections.Generic;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Web;

namespace PharmacyAndStock.BL
{
    public class UnitOfWork
    {
      
        private HospitalEntities db = new HospitalEntities();

        public HospitalSettingsManager HospitalSettingsManager
        {
            get
            {
                return new HospitalSettingsManager(db);
            }
        }

     

    


       
 

        public void Save()
        {
            db.SaveChanges();
        }
      
    }
}