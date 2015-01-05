mandolin
=========

slices up gherkin to make it more versatile

gherkin is great but scenarios have embedded data used for the testing

mandolin can do the following:

    strip out tables and place each one in a pipe separated file
    merge a feature file (containing table placeholders) with a set of pipe separated data files


how it works:


what mandolin doesn't yet do:
work with Windows - wouldn't be a big deal to do this...
pull data files from other paths/urls

what also considering:
allowing locators to be extracted (not sure this is a good idea) and then merged back in (this seems like a better idea) for management as a repository