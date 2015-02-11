$packages = [
    'php5-cli'
]

package { $packages:
    ensure => present
}
