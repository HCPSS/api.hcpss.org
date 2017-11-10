# HCPSS API 2.0

HCPSS API 2.0 is a rewrite of the HCPSS API to be 
[JSON API](http://jsonapi.org/) compatible.

## Platform

2.0 is build on Silex and uses a schemaless flat file JSON database. This 
allows us to track our changes to the data through version control.

## JSON API

### Better Queries.

JSON API allows us to serve our data in a much more dynamic way than was 
possible with the 1.0 version of the API. For example, let's say you want to 
know what the mascots are for all high schools. With API 1.0 you would make 
a call to `/schools.json` and get a list of schools. Then you need to loop over
the high schools and make a request to each of them. That's 13 requests. 

By contrast in version 2.0, you would make 1 request for this information 
like this `/facility?filter[level]=hs`.

An even worse example would be trying to get all schools with the PBIS 
achievement. Version 1.0 would require 77 requests. With 2.0, it's as simple 
as `/facility?filter[achievements.pbis.id]=pbis` or 
`/achievement/pbis/facilities`.

### Smaller Payloads

To make matters worse, the 1.0 responses contain far more information about the 
school than you are interested in. Information about address, principal, 
achievements, etc.

In 2.0 we can trim this information to only what we are interested in by 
adding `&fields[facility]=mascot` to our query. We'll get something like 
this:

```json
{
    "data": [
        {
            "type": "facility",
            "id": "ahs",
            "attributes": {
                "mascot": "Raiders"
            },
            "links": {
                "self": "http:\/\/localhost:8099\/facility\/ahs"
            }
        },
        {
            "type": "facility",
            "id": "chs",
            "attributes": {
                "mascot": "Eagles"
            },
            "links": {
                "self": "http:\/\/localhost:8099\/facility\/chs"
            }
        },
        {"etc": "etc..."}
    ]
}
```

### Better Standards

Additionaly, users can expect more consistency in the data they get. For 
instance, the 1.0 listing of schools (`/schools.json`) gives you a listing of
school acronyms grouped by education level:

```json
{
    "school_bus_locator": "https://www.infofinderi.com/ifi/?cid=HCP2IOASIJVW",
    "online_payments": "https://osp.osmsinc.com/HowardMD/BVModules/CategoryTemplates/Detailed%20List%20with%20Properties/Category.aspx?categoryid=DA011",
    "schools": {
        "ec": [
            "arl",
            "cls",
            "hc"
        ],
        "es": [
            "aes",
            "bbes",
            "bpes",
            "etc..."
        ]
    }
}
```

Why? because that's what I thought would be most usefull. But another user 
may not feel the same way. Also, there is no way for a user to know what the 
URL is for each school. They have to guess.

In 2.0 resources are formatted in a standard way and contain a reference to
their canonical URL.
 
### Schemaless Flat File Database

The data for the API is kept in JSON file in /data. Each directory within 
/data represents a resource type. The files are schemaless so you can add 
data to them whever you want. Just like in version 1.0.

The only exception to this is relationships. Relationships must be managed in
`/data/schema.json`.

#### Relationships

Relationships are managed in a central JSON file located at 
`/data/schema.json`. At this time, many-to-many, has-many, and has-one 
relationships are supported.

## Commands

This version comes with 2 commands.

```
$ ./bin/console cache:clear
```

Clears all cache.

```
$ ./bin/console data:refresh
```

This command pulls the data from version 1.0 of the API and updates the data 
in 2.0 from it.








