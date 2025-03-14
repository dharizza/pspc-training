# PSPC Training

This repository is used for training. There are multiple Pull requests that include the work done on each training sessions. You can use this to get up to speed or re-install a site from a particular point.
The following is a list of the steps you should follow to setup this repository in your environment, you can start from any of the branches.

## Directions

1. Clone this repository
2. Checkout to the branch you want to start from, for example if you want to start from site-building-02 then run the command `git checkout site-building-02`
3. From the terminal move to the folder of the training, for example `cd pspc-training`
4. Start the containers by running `ddev start`
5. Install the website by running the command `ddev drush si --existing-config`
6. Login to your website by runnin `ddev drush uli` and visiting that URL
