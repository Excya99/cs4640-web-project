# cs4640-web-project
A project I worked on by myself in a web development class.

It is the beginning of a financial web application that would allow users to "simulate" and manage stock portfolios. As it is the "Portfolio" tab is the only one with functioning code.
Source code includes comments for readability and improvements if project were to continue being developed for fun.

## Breakdown
1. MVC model with a PHP controller set up
2. PHP back-end, including database (MySQLi) connection
3. JavaScript front-end (uses jQuery)
4. Involves asynchronous functions (AJAX requests)
5. HTML/CSS for templates, imported and utilized Bootstrap library
6. Very dependent on Polygon.io stock API
    - functions only work for Polygon.io's stock API
    - requires APIKey that was omitted (subscription keys from Polygon.io would allow improvements as noted in comments)
