using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace PharmacyAndStock.BL
{
    public interface IRepository<TEntity>
    {
        List<TEntity> GetAllBind();
        IQueryable<TEntity> GetAll();
        TEntity GetById(params object[] Id);
        TEntity Add(TEntity entity);
        void Update(TEntity entity);
        void Delete(TEntity entity);
    }
}