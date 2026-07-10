-- Truncate existing data to insert fresh menu
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE menu;
TRUNCATE TABLE categories;
SET FOREIGN_KEY_CHECKS = 1;

-- Insert Categories
INSERT INTO categories (name, image) VALUES
('Fried Rice', 'fried_rice_premium_1783450935214.png'),
('Noodles', 'noodles_premium_1783450946325.png'),
('Specials', 'manchurian_premium_1783450962627.png'),
('Starters', 'momos_premium_1783451008946.png'),
('Burgers', 'burger_premium_1783450998976.png'),
('Al-Baik Specials', 'chicken_starters_premium_1783450986638.png');

-- Insert 28 Menu Items
INSERT INTO menu (name, description, price, category_id, type, image) VALUES 
-- 1. Fried Rice
('Paneer Fried Rice', 'Premium quality wok-tossed fried rice with soft paneer chunks.', 299.00, (SELECT id FROM categories WHERE name='Fried Rice'), 'Veg', 'fried_rice_premium_1783450935214.png'),
('Paneer Chilly Fried Rice', 'Spicy wok-tossed fried rice with chilly paneer.', 299.00, (SELECT id FROM categories WHERE name='Fried Rice'), 'Veg', 'fried_rice_premium_1783450935214.png'),
('Chilly Garlic Fried Rice', 'Aromatic fried rice packed with garlic and chilly flakes.', 329.00, (SELECT id FROM categories WHERE name='Fried Rice'), 'Veg', 'fried_rice_premium_1783450935214.png'),
('Schezwan Fried Rice', 'Authentic spicy schezwan style fried rice.', 249.00, (SELECT id FROM categories WHERE name='Fried Rice'), 'Veg', 'fried_rice_premium_1783450935214.png'),

-- 2. Noodles
('Veg Noodles', 'Classic stir-fried vegetable noodles.', 249.00, (SELECT id FROM categories WHERE name='Noodles'), 'Veg', 'noodles_premium_1783450946325.png'),
('Chilly Garlic Noodles', 'Garlic infused spicy noodles tossed with veggies.', 269.00, (SELECT id FROM categories WHERE name='Noodles'), 'Veg', 'noodles_premium_1783450946325.png'),
('Schezwan Noodles', 'Spicy schezwan pepper noodles.', 259.00, (SELECT id FROM categories WHERE name='Noodles'), 'Veg', 'noodles_premium_1783450946325.png'),
('Paneer Noodles', 'Wok-tossed noodles loaded with fresh paneer cubes.', 299.00, (SELECT id FROM categories WHERE name='Noodles'), 'Veg', 'noodles_premium_1783450946325.png'),
('Chicken Noodles', 'Stir-fried noodles with tender chicken pieces.', 329.00, (SELECT id FROM categories WHERE name='Noodles'), 'Non Veg', 'noodles_premium_1783450946325.png'),

-- 3. Manchurian & Specials
('Veg Manchurian', 'Crispy vegetable balls tossed in rich dark soy sauce.', 249.00, (SELECT id FROM categories WHERE name='Specials'), 'Veg', 'manchurian_premium_1783450962627.png'),
('Chicken Manchurian', 'Succulent chicken pieces in spicy Manchurian sauce.', 329.00, (SELECT id FROM categories WHERE name='Specials'), 'Non Veg', 'manchurian_premium_1783450962627.png'),
('Drums of Heaven (Half)', 'Premium chicken drumsticks tossed in sweet and spicy sauce.', 369.00, (SELECT id FROM categories WHERE name='Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Chicken Lollipop (Half - 3 pcs)', 'Crispy, juicy chicken lollipops.', 200.00, (SELECT id FROM categories WHERE name='Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Chicken Lollipop (Full)', 'Crispy, juicy chicken lollipops (Full Portion).', 349.00, (SELECT id FROM categories WHERE name='Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),

-- 4. Chinese Starters
('Crispy Corn', 'Golden fried sweet corn kernels tossed with spices.', 299.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'manchurian_premium_1783450962627.png'),
('Chilly Potato', 'Crispy potato wedges glazed in sweet and spicy chilly sauce.', 279.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'manchurian_premium_1783450962627.png'),
('Honey Chilly', 'Sweet and spicy honey chilly bites.', 299.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'manchurian_premium_1783450962627.png'),
('Chicken Honey Chilly', 'Tender chicken glazed in premium honey chilly sauce.', 329.00, (SELECT id FROM categories WHERE name='Starters'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Veg Momos', 'Steamed dumplings stuffed with finely chopped vegetables.', 149.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'momos_premium_1783451008946.png'),
('Paneer Momos', 'Steamed momos loaded with soft paneer filling.', 199.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'momos_premium_1783451008946.png'),
('Chicken Momos', 'Juicy minced chicken steamed dumplings.', 249.00, (SELECT id FROM categories WHERE name='Starters'), 'Non Veg', 'momos_premium_1783451008946.png'),

-- 5. Additional Items
('Spring Roll', 'Crispy rolls stuffed with mixed vegetables.', 199.00, (SELECT id FROM categories WHERE name='Starters'), 'Veg', 'manchurian_premium_1783450962627.png'),
('Chicken 65', 'Spicy, deep-fried chicken signature dish.', 365.00, (SELECT id FROM categories WHERE name='Starters'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),

-- 6. Burgers
('Veg Burger', 'Crispy vegetable patty with fresh lettuce and sauces.', 169.00, (SELECT id FROM categories WHERE name='Burgers'), 'Veg', 'burger_premium_1783450998976.png'),
('Chicken Burger', 'Juicy chicken patty, premium cheese and fresh lettuce.', 199.00, (SELECT id FROM categories WHERE name='Burgers'), 'Non Veg', 'burger_premium_1783450998976.png'),

-- 7. Al-Baik Specials
('Chicken Popcorn', 'Bite-sized crispy fried chicken.', 149.00, (SELECT id FROM categories WHERE name='Al-Baik Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Chicken Strips', 'Tender, juicy, and crispy chicken strips.', 249.00, (SELECT id FROM categories WHERE name='Al-Baik Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Chicken Wings', 'Crispy hot chicken wings.', 259.00, (SELECT id FROM categories WHERE name='Al-Baik Specials'), 'Non Veg', 'chicken_starters_premium_1783450986638.png'),
('Chicken Double Dong', 'Special double patty crispy chicken delight.', 199.00, (SELECT id FROM categories WHERE name='Al-Baik Specials'), 'Non Veg', 'burger_premium_1783450998976.png'),
('Chicken Burger (Tandoori)', 'Tandoori flavored spicy chicken burger.', 159.00, (SELECT id FROM categories WHERE name='Burgers'), 'Non Veg', 'burger_premium_1783450998976.png'),
('Chicken Burger (Achari)', 'Tangy achari flavored chicken burger.', 199.00, (SELECT id FROM categories WHERE name='Burgers'), 'Non Veg', 'burger_premium_1783450998976.png'),
('Veg Burger (Small)', 'Classic small veg burger.', 99.00, (SELECT id FROM categories WHERE name='Burgers'), 'Veg', 'burger_premium_1783450998976.png');

-- Update some items to be featured on homepage
UPDATE menu SET is_special = 1 WHERE name IN ('Paneer Fried Rice', 'Chicken Burger', 'Chicken Noodles');
