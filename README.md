<h1 align="center"><img src="https://i.imgur.com/uoa5gPP.png?" alt="Logo" /></h1>

## :pushpin: Overview

The HR Web Application is an application that will take the process of job bidding and shifting with the Mercury Union online to allow more ease for both the union workers and HR representatives. Previously, the entire bidding process had been done by hand - whereas this application will be completely automated. 

This application is written in the follow languages: `html` `css` `php` `js` `sql`

## :question: Problem Statement

Whenever a union worker wants to shift jobs, they must go to the HR office at Mercury and hand write their bids for a different job. Then, the HR staff must go through each bid, order them based on criteria such as in-department vs out-of-department, seniority, and whether they have a CDL/JWC. Currently, this process takes several hours of man power that could potentially work within minutes. 

## :arrows_counterclockwise: Page Breakdown

#### :one: /index.php

The index page is the main router page - using the .htaccess file, it is setup so that any time a user tries to access a different directory *(i.e. /listing/)* they will be redirected to the index.php page. Based on what directory they were redirected from, they will be shown that page using **require()** and the template.php page (2).

#### :two: /assets/templates/template.php

This page acts as the template for all pages. This consists of the global js file, a boiler plate of headers and divs, and the php portion that will start the session for the user. Through template.php, the user's session is checked to see if they are logged in. If not, they are redirected to the homepage.

#### :three: /assets/templates/listing.php

This page is the main page that the user is taken to. They are given a list of available postings to look at and bid on. It will display either `Eligible` or `Ineligible` depending on how long they have been working for Mercury (at least one year) and the last time they changed jobs (at least 6 months ago, or never). Future functionality includes searching for jobs and filtering jobs.

#### :four: /assets/templates/profile.php

On this page, the user will be able to see all the jobs they have bid on and set preferences for them. The user is warned if they do not set all of their preferences, they will be set for them through the algorithm which is run at the end of bidding process (beginning of every week, Tuesday). Future functionality includes removing a job if they accidentally bid on a job they are not interested in.

#### :five: /assets/templates/hr.php

This is the backend side for HR representatives only. This page will show all the jobs available for bid and show the user the amount of bids on each job. In the future, this page will display the winners/losers after the algorithm has successfully run. The HR representative will then be able to choose the winner for each. Another upcoming feature will be the ability to remove jobs from the listing if they were accidentally added.

#### :six: /assets/templates/addjob.php

This page will allow the HR representatives to add jobs to the listing. This page features four drop down menus for job title, job shift, job plant, and job department. Each drop down choices are derived from a database for each listing. This will allow the HR department to change the values for each in the future - as we believe these may change in the future. 

#### :seven: /assets/templates/updateworkers.php

This page is run once per week by the HR department to update the worker's information in case they had changed from the week before. This takes a **.csv** file and processes it. The file should be a report received by Emily Steger that contains all the worker's information from WorkDay. The program that processes the file assumes the file has not been touched since receiving it, and thus contains four lines of unnecessary data and subsequently skips it. Then, takes the remaining information, parses each column and inserts it into a database. This program runs after the database has been cleared first.

#### :eight: /assets/func/alg.func.php

This is a backend file that does not feature any front end design. This file was designed to be run every Tuesday at 9am and will be only run by a cronjob. The process of the algorithm is intended to run as follows:

1. Gather all the bids and find bids that have not had their preferences yet. We find who those bids belong to, then go through each user we found and set unset bids. The alogorithm does this by finding the highest set preference (in most cases, will be 0) and then start setting the unset preferences to be (max preference)+1. 

2. Then, the algorithm gathers ALL of the bids and sorts them by job. 

3. Iterate through each job and create two arrays: (1) bids from users within the same department of the job (2) bids from users outside the department of the job. Then, we sort each array by their seniority date and merge the two arrays together.
