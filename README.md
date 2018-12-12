# user_upload
Catalyst Testing

Assumption #1

"The script should validate the email address before inserting to make sure that it is valid (valid
means that it is a legal email format, e.g. “xxxx@asdf@asdf” is not a legal format). In the
instance that an email is invalid, no insert should be made to the database and an error
message reported to STDOUT"

The sentence segment "no insert should be made to the database and an error
message reported to STDOUT" can be read in two ways. 

It could be read that:
1. No insert should be made and no error should be printed.
2. No insert should be made and an additional meassage should be printed

For this I printed a message when email duplicates or invalid emails were found. 