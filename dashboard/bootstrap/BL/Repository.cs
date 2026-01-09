
using PharmacyAndStock.Models;
using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace PharmacyAndStock.BL
{
    public class Repository<TEntity> : IRepository<TEntity> where TEntity : class
    {
        private HospitalEntities db;
        private DbSet<TEntity> _Set;

        public Repository(HospitalEntities _db)
        {
            db = _db;
            _Set = db.Set<TEntity>();
        }

        public virtual TEntity Add(TEntity entity)
        {
            _Set.Add(entity);
            return db.SaveChanges() > 0 ? entity : null;
        }

     

        public virtual void Delete(TEntity entity)
        {   
            _Set.Remove(entity);

        }

        public IQueryable<TEntity> GetAll()
        {
            return _Set;
        }

        public List<TEntity> GetAllBind()
        {
            return GetAll().ToList();
        }

        public  TEntity GetById(params object[] Id)
        {
            return _Set.Find(Id);
        }

        public virtual void Update(TEntity entity)
        {
            _Set.Attach(entity);
            db.Entry(entity).State = EntityState.Modified;
     
        }

        public virtual TEntity UpdateData(TEntity entity)
        {
            _Set.Attach(entity);
            db.Entry(entity).State = EntityState.Modified;
            return db.SaveChanges() > 0 ? entity : null;
     
        }

    }
   
}