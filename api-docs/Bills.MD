## Bills api
**Get bills**
[GET] `/api/accounting/bills`

**Create bills**

[POST] `/api/accounting/bills`
```json
{
    "name": "Factura tigo papas",
    "description": "...",
    "apply_date": "2021-01-01",
    "apply_day": 15,
    "custom_days": "l,m,x,j,v,s,d",
    "frequency": 3,
    "value": 110000,
    "is_open": true
}
```

**Update bills**

[PATCH] `/api/accounting/bills/{record}`
```json
{
    "name": "Factura tigo papas",
    "description": "...",
    "apply_date": "2021-01-01",
    "apply_day": 18,
    "custom_days": "l,m,x,j,v,s,d",
    "frequency": 3,
    "value": 110000,
    "is_open": true
}
```

**Remove bills**

[DELETE] `/api/accounting/bills/{record}`
