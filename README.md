# Ecommerce Multivender App



Admin UI:

on splash screen, loads the base-categories, sub-categories and categories on the local storage.

when user decides to add new product, steps are:

1. Choose Base-Category
2. Choose Category
3. Choose Sub-Category
// all these are fetched from local storage and showed in dropdown or related ui.

4. Load the projuct structure based on the sub-category from the server. Render the form based on the supplied data. After filling the form submit the data to the server.

<!-- The from would contains the sub-variations if needed. The sub-variations ui is a new page (stack) and the items are added 
    are showed in products page. This can be edited and added multiple at once.  -->

Note: Once the app is launched, it is very hard to edit the sub-category. For now, It's impossible.

To edit or delete the product, Admin can search the product on products section or just simply browse by category.

Note: In the user app, if admin is logged in by their credintials, they can directly redirect to the admin app by a button on product page which is only visible to the admin.
