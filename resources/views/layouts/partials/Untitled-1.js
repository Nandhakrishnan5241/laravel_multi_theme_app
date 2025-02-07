table_name : collections
id, collectionName,  slug, collectionDefinition, collectionData, created_at, updated_at, deleted_at

1
Category
category
[
    {
    'id' : 1,
    'name' : 'name',
    'label' : 'Category Name',
    'type' : 'textbox',
    'display' : 1,
    'order' : 1

    },
    {
    'id' : 2,
    'name' : 'description',
    'label' : 'Category Description',
    'type' : 'textbox',
    'display' : 1,
    'order' : 2
    },
    {
    'id' : 3,
    'name' : 'image',
    'label' : 'Category Image',
    'type' : 'file',
    'display' : 1,
    'order' : 3
    }
],

[
    {
        'id' : 1,
        'name' : 'Apparel',
        'slug' : 'apparel',   
        'image' : '../../images/category/1/apparel.jpg',
        'descripton' : 'apparel description',    
        'order' : 1,
        'created_at' : '06-02-2025',
        'updated_at' : '07-02-2025'    
    },
    {
        'id' : 2,
        'name' : 'Electronics',
        'slug' : 'electronics',   
        'image' : '../../images/category/2/electronics.jpg',
        'descripton' : 'electronics description',    
        'order' : 2,
        'created_at' : '07-02-2025',
        'updated_at' : '08-02-2025'
    
    },
    {
        'id' : 3,
        'name' : 'Vegetables',
        'slug' : 'vegetables',   
        'image' : '../../images/category/3/vegetables.jpg',
        'descripton' : 'electronics description',    
        'order' : 3,
        'created_at' : '07-02-2025',
        'updated_at' : '08-02-2025'
    
    },

]

2
SubCategory
[
    {
        'id' : 1,
        'name' : 'name',
        'label' : 'Sub Category Name',
        'type' : 'textbox',
        'display' : 1,
        'order' : 1
    
        },
        {
        'id' : 2,
        'name' : 'category',
        'label' : 'Category ',
        'type' : 'dropdown',
        'display' : 1,
        'order': 2
        },
        {
        'id' : 3,
        'name' : 'description',
        'label' : 'Sub Category Description',
        'type' : 'textbox',
        'display' : 1,
        'order': 3
        },
        {
        'id' : 4,
        'name' : 'image',
        'label' : 'Sub Category Image',
        'type' : 'file',
        'display' : 1,
        'order': 4
        }
],

[
    {
        'id'   : 1,
        'name' : 'Shirts',
        'slug' : 'shirts',  
        'category_id' : 1,
        'category_name' : 'Apparel', 
        'image' : '../../images/subcategory/1/shirt.jpg',
        'descripton' : 'shirts description',    
        'order' : 1,
        'created_at' : '06-02-2025',
        'updated_at' : '07-02-2025'    
    },
    {
        'id'   : 2,
        'name' : 'TV',
        'slug' : 'tv',  
        'category_id' : 2,
        'category_name' : 'Electronics', 
        'image' : '../../images/subcategory/2/apparel.jpg',
        'descripton' : 'apparel description',    
        'order' : 2,
        'created_at' : '07-02-2025',
        'updated_at' : '08-02-2025'    
    },
    {
        'id'   : 3,
        'name' : 'Carrot',
        'slug' : 'carrot',  
        'category_id' : 3,
        'category_name' : 'Vegetab les', 
        'image' : '../../images/subcategory/3/carrot.jpg',
        'descripton' : 'carrot description',    
        'order' : 3,
        'created_at' : '07-02-2025',
        'updated_at' : '08-02-2025'    
    },

]
