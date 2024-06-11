# DigiForge User Manual (Patent Pending)
### Professor Sanmeet Kaur
### Eastern Washington University CSCD350 Spring 2024

## About Us (Team 8)
1. Tyler Holmquist (tholmquist@ewu.edu)
2. Andree Ramirez (aramirez61@ewu.edu)
3. Cael Foster (cfoster19@ewu.edu)
4. Paul Aguilar (paguilar4@ewu.edu)
5. Kaimana Kahalekai (kkahalekai@ewu.edu)

## Table of Contents
1. [Introduction](#introduction)
2. [Project Scope](#project-scope)
3. [Getting Started](#getting-started)
   1. [Prerequisites](#prerequisites)
   2. [Installing the VM](#installing-the-vm)
   3. [Accessing the Website](#accessing-the-website)
4. [Editing the Contact Form Notification Plugin](#editing-the-contact-form-notification-plugin)
   1. [Accessing wp-content](#accessing-wp-content)
   2. [Customizing Notification Settings](#customizing-notification-settings)
   3. [Testing Changes](#testing-changes)
5. [Database Access](#database-access)
6. [Troubleshooting](#troubleshooting)
7. [Contact Information](#contact-information)


## Introduction
Welcome to DigiForge! This manual is designed to help you get started with our project, a redesigned website for a local architecture firm in Spokane, WA. The following sections will guide you through the setup and usage of the provided virtual machine (VM), which hosts the website.

## Project Scope
The project's scope includes the following requirements: 

(*Can't see on github*) (<span style="color:red">Red not complete</span>,<span style="color:yellow">yellow in progress</span>,<span style="color:green">green complete</span>)

- <span style="color:yellow">Time-based contact inquiry form</span>
- <span style="color:green">Connected to a database that records responses from the contact form</span>
- <span style="color:green">Complete redesign of the home page</span>
- <span style="color:green">Updated about and contact pages</span>
- <span style="color:green">Implemented a way to sort projects</span>

## Getting Started

### Prerequisites
- A virtualization software (such as VirtualBox or VMware) installed on your computer.
- The provided .ova file of the VM (in Google Drive).

### Installing the VM
1. **Download the .ova file:** Obtain the provided .ova file for the DigiForge VM.
2. **Open VirtualBox/VMware:**
   - If using VirtualBox, go to `File` > `Import Appliance`.
   - If using VMware, go to `File` > `Open`.
3. **Import the .ova file:**
   - Select the .ova file you downloaded.
   - Follow the on-screen instructions to import the VM.
4. **Start the VM:**
   - After the import is complete, select the DigiForge VM from your list of virtual machines and start it.

### Accessing the Website
Once the VM is running:
1. **Open a web browser** on your host machine.
2. **Navigate to localhost:** The website is configured to run on `localhost` with appropriate port forwarding set up.
   - Open `http://localhost` in your browser.
   - Open `http://localhost/wp-admin` in your browser for the admin panel.
   - **WP Admin Credentials:**
   - Username: `kkahalekai@ewu.edu`
   - Password: `fusion_architect1234`
3. **Explore the Website:**
   - The website should load, and you can navigate through the home page, about page, contact page, and project sorting functionality.

### Database Access
The contact form on the website is connected to a database that stores user inquiries.
To access and manage this database at http://localhost/phpmyadmin: 
1. **Database Credentials:**
   - Username: `wordpress`
   - Password: `fusion_architect1234`
2. **Database Management Tool:**
   - Use a tool like phpMyAdmin (if installed) or any SQL client to connect to the database.
   - The database server is running locally on the VM, accessible at `localhost`.

### Troubleshooting
- **Website not loading:** Ensure the VM is running and check that your host machine's network settings allow for localhost access.
- **Database connection issues:** Verify the database server is running on the VM and the credentials are correct.
- **VM performance issues:** Allocate more RAM or CPU resources to the VM through your virtualization software's settings.

## Editing the Contact Form Notification Plugin

### Accessing wp-content
The contact form notification plugin is located in the `wp-content` directory of the website's WordPress installation. To edit the plugin:

1. **Access the VM File System:**
   - Use a file manager or an SSH client to access the VM's file system.
   - Navigate to the WordPress installation directory, typically located at `/home/class-vm/github-directory/public_html/wp-content/plguins`.

2. **Locate the Plugin Directory:**
   - Inside the `wp-content` directory, navigate to `plugins/contact-form-notification`.
   - Here you will find the files related to the contact form notification plugin.

3. **Editing Plugin Files:**
   - Use a text editor (such as Vim, Nano, or an IDE like Visual Studio Code if you have remote access set up) to edit the plugin files.
   - Common files to edit include `contact-form-notification.php` and any custom scripts or stylesheets associated with the plugin.

### Customizing Notification Settings
1. **Open the Main Plugin File:**
   - Edit `contact-form-notification.php` to modify the behavior of the plugin.
   - Look for functions handling form submission and email notifications.

2. **Update Email Settings:**
   - Ensure the email address for receiving notifications is correct.
   - Customize the email template and content as needed.

3. **Save Changes:**
   - After making your changes, save the files and restart the web server if necessary to apply the updates.

### Testing Changes
1. **Submit a Test Form:**
   - Go to the contact form on the website and submit a test inquiry.
2. **Check Email Notifications:**
   - Verify that the notification is sent to the configured email address.
   - Ensure the content of the email matches the customized template.




## Contact Information
For further assistance, please reach out to any of the team members:
- Tyler Holmquist: tholmquist@ewu.edu
- Andree Ramirez: aramirez61@ewu.edu
- Cael Foster: cfoster19@ewu.edu
- Paul Aguilar: paguilar4@ewu.edu
- Kaimana Kahalekai: kkahalekai@ewu.edu

Thank you for using DigiForge! We hope this manual helps you effectively set up and use the provided VM and website.