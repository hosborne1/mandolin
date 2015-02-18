mandolin
=========

slices up gherkin to make it more versatile

gherkin is great but scenarios and scenario outlines have embedded data used for the testing

mandolin can extract tables to and merge them back in again when:

The idea is that tables will be managed separately so that you can run the same scenario or scenario outline with different data. Why do this?

1. You may need different data for working in different environments

2. You may want to have different testing scope. e.g a table with several rows for one type of testing then a table with several thousand rows for a different type. You choose...

