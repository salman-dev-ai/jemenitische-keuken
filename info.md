email :salman@gmial.com

password:
11111111

username:salman

create file language
php artisan lang:publish


If you’d like to save time, Filament can automatically generate the form and table for you, based on your model’s database columns, using --generate:
php artisan make:filament-resource Customer --generate



كيف  اضيف  زر لي  الرجوع الى الصفحه التاليه في  الهواتف


1. php artisan optimize:clearالوظيفة: حذف ملفات الكاش (التخزين المؤقت) العامة للنظام.


 php artisan view:clearالوظيفة: مسح كاش ملفات الواجهات (Blade Views).


 Ctrl + F5 (تحديث صلب للتحميل)الوظيفة: إعادة تحميل الصفحة في متصفح الإنترنت مع مسح كاش المتصفح.


 حاول تحديث الكاش الخاص بـ routes عبر الأمر:


          
            
            
          
          php artisan route:clear
php artisan route:cache


php artisan make:livewire MenuCategories

 
